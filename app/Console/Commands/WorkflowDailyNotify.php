<?php

namespace App\Console\Commands;

use App\Models\Variation;
use App\Models\Workflow;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\QueryException;

class WorkflowDailyNotify extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'workflow:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Informa ogni utente il totale dei documenti da firmare via email.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
		
        $users = User::permission(['workflow_approval','variation_approval'])->get();
		//$users = User::all();
        foreach ($users as $user){
            $workflow_commessa = $workflow_cnfOrdine = $workflow_Revisione = $workflow_variazioni = 0;
            if($user->hasPermissionTo('workflow_approval')){
                $workflow_commessa = Workflow::select('count(*) as allcount')
                    ->leftJoin('workflow_users', 'workflow_users.Workflow', 'workflows.id')
                    ->Where('workflow_users.user', '=', $user->id)
                    ->WhereNull('workflow_users.aprovato')
                    ->Where('workflows.type', '=', 1)
                    ->count();
                $workflow_cnfOrdine = Workflow::select('count(*) as allcount')
                    ->leftJoin('workflow_users', 'workflow_users.Workflow', 'workflows.id')
                    ->Where('workflow_users.user', '=', $user->id)
                    ->WhereNull('workflow_users.aprovato')
                    ->Where('workflows.type', '=', 2)
                    ->count();
                $workflow_Revisione = Workflow::select('count(*) as allcount')
                    ->leftJoin('workflow_users', 'workflow_users.Workflow', 'workflows.id')
                    ->Where('workflow_users.user', '=', $user->id)
                    ->WhereNull('workflow_users.aprovato')
                    ->Where('workflows.type', '=', 3)
                    ->count();
            }

            if($user->hasPermissionTo('variation_approval')){
                $workflow_variazioni = Variation::select('count(*) as allcount')
                    ->leftJoin('variation_users', 'variation_users.variations', 'variations.id')
                    ->Where('variation_users.user', '=', $user->id)
                    ->WhereNull('variation_users.aprovato')
                    ->count();
            }

            $data=[
                'commesse' => $workflow_commessa,
                'revisioni' => $workflow_Revisione,
                'conferme_ordine' => $workflow_cnfOrdine,
                'variazioni' => $workflow_variazioni,
                'name' => $user->firstname.' '.$user->lastname,
            ];

            if($workflow_commessa > 0 || $workflow_cnfOrdine > 0 || $workflow_Revisione > 0 || $workflow_variazioni > 0 )
                Mail::send('/content/apps/workflow/email/notilydaily', $data, function($message) use ($user) {
                    $message->to($user->email,Ucwords($user->firstname).' '.Ucwords($user->lastname))->subject
                    ('Resoconto Giornaliero Workflow');
                    $message->from('info@mbcavi.it','Gestionale Metallurgica');
                });

        }
		\Log::info("Notifiche inviate");

    }
}
