<?php

namespace App\Models;

use App\Http\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Advisory extends Model
{
    use HasFactory,UsesUuid;

#TODO aggiornare migration
    protected $fillable = [
        'id', 'head', 'order', 'father', 'material_1', 'material_description_1', 'division', 'total_order_quantity', 'um', 'quantity_1', 'operation_activity',
        'operation_activity_row','work_center', 'work_center_cost', 'confirmed_quantity', 'activity_machine_hours', 'machine_cost', 'activity_manpower_hours',
        'manpower_cost', 'machine_cost_manpower', 'calculation_1', 'material_description_2', 'requirement_quantity', 'base_unit_measure',
        'quantity_2', 'material_cost', 'cost_material','calculation_2', 'total_raw_material', 'total_machine_cost_manpower', 'final_total',
        'raw_material_machine_manpower_cost', 'material_2', 'date_created', 'created_at', 'updated_at', 'check_row', 'view',
        'qty_perv_fase', 'um_fase','calculated','macchine_cost_real','machine_cost_theoretic','manpower_cost_real','manpower_cost_theoretic','macchine_manpower_cost_theoretic'
    ];

    private $camps = [
        0 => 'order', 1 => 'material_1', 2 => 'material_description_1', 3 => 'division', 4 => 'total_order_quantity', 5 => 'um', 6 => 'quantity_1', 7 => 'operation_activity',
        8 => 'work_center', 'machine_cost' => 'work_center_cost', 10 => 'confirmed_quantity', 11 => 'activity_machine_hours', 'machine_cost_total' => 'machine_cost', 13 => 'activity_manpower_hours',
        'manpower_cost_total' => 'manpower_cost', 'machine_cost_manpower' => 'machine_cost_manpower',  15 => 'material_2', 16 => 'material_description_2', 17 => 'requirement_quantity', 18 => 'base_unit_measure',
        19 => 'quantity_2', 20 => 'qty_perv_fase', 21 => 'um_fase', 22 => 'material_cost', 23 => 'total_raw_material', 24 => 'total_machine_cost_manpower', 25 => 'final_total',

    ];

    public static $matirial_order=[
        0=>'FC',      1=>'SF',
        2=>'PE',      3=>'SZ',
        4=>'BU',      5=>'CO',
    ];

    private $data_material = '';

    public function stored($data_materiali,$data_macchinari,$row, $head, $save_cleck, $father = null, $infoOrder = null)
    {
        try {
            $machine_cost = 0;
            $material_cost = 0;
            $this->data_material = $data_materiali;
            foreach ($this->camps as $key => $camp) {
                //Log::channel('stderr')->info('NUMERO: ' . $this->order_row . ' KEY: ' . $key);
                switch ($key) {
                    case 8:
                        if ($row[$key]) {
                            $machine_cost = MachineLaborCosts::Select('cost')->where('machine', '=', $row[$key])->where('date_import','=',$data_macchinari)->first()->cost;
                        }
                        $this->$camp = $row[$key];
                        break;
                    case 11:
                    case 13:
                        if ($row[$key]) {
                            $this->$camp = round((float)$row[$key] / 60,2);
                        }
                        break;
                    case 19:
                        if ($row[$key])
                            $this->$camp = str_replace("-", "", $row[$key]);
                        break;
                    case 20:
                    case 21:
                        if (!empty($row[$key]))
                            $this->$camp = $row[$key];
                        break;
                    case 22:
                        if ($this->quantity_2) {
                            $this->$camp = round((float)$material_cost * (float)$this->quantity_2, 2);
                        }
                        break;
                    case 15:
                        if ($row[$key]){
                            $material_cost = MaterialsCost::Select('cost')->where('material', '=', $row[$key])->where('date_import','=',$data_materiali)->first()->cost;

                            $matirial_obj = Advisory::all()
                                ->where('material_1', '=', $row[$key])
                                ->where('head', '=', $head->id)
                                ->first();
                            if($matirial_obj){
                                $matirial_block = Advisory::all()
                                    ->where('father', '=', $matirial_obj->order)
                                    ->where('head', '=', $head->id)
                                    ->get();
                            }
                            // CONTROLLARE: SE ESISTE IL BLOCCO SOMMARE E IL TOTALE è IL COSTO MATERIALE, ALTRIMENTI RICERCARE IL MATERIAL NELLA TABELLA
                            //$this->cost_material = $material_cost;
                        }
                        $this->$camp = $row[$key];
                        break;
                    case 23:
                    case 24:
                        break;
                    case 'machine_cost_manpower':
                        if ($this->machine_cost)
                            $this->$camp = $this->machine_cost + $this->manpower_cost;
                        break;
                    case 'machine_cost':
                        if ($machine_cost) {
                            $this->$camp = $machine_cost;
                        }
                        break;
                    case 'material_cost':
                        if ($material_cost){
                            $this->$camp = $material_cost;
                        }
                        break;
                    case 'machine_cost_total':
                        if ($machine_cost)
                            $this->$camp = $machine_cost * $this->activity_machine_hours;
                        break;
                    case 'manpower_cost_total':
                        if ($this->activity_manpower_hours > 0) {
                            $manpower_cost = Greneric::find(1);
                            $this->$camp = $manpower_cost->manpower * $this->activity_manpower_hours;
                        }
                        break;

                    default:
                        $this->$camp = $row[$key];
                }
            }

            if (!$row[0]) {
                $this->father = $father;
            } else {
                $this->order = $row[0];
                $infoOrder['order'] = $row[0];
                $infoOrder['tot_order_qty'] = (float)$this->total_order_quantity;
                $infoOrder['quantity'] = (float)$this->quantity_1;

            }
            $this->head = $head->id;
            $this->view = true;
            $this->save();
            return $infoOrder;
        } catch (\Exception $e) {
            //Log::channel('stderr')->info('  QUIIIII!!!! ');
            //Log::channel('stderr')->info($row);
            // return redirect(route('users.index'));
        }
    }


    private function calcBlock($row, $head){
        $matirials = Advisory::all()
            ->where('father', '=', $row->order)
            ->where('head', '=', $head->id)
            ->get();
        foreach ($matirials as $matirial)
            $this->searchMaterial($matirial);

        $total_matarial = DB::table('advisories')
            ->where('father', '=', $row->order)
            ->where('head', '=', $row->head)
            ->sum(DB::raw('matarial_cost'));

        $row->matarial_cost = (float)$total_matarial;
        $row->save();
    }

    private function calcMaterial($row, $head){
        $cost = MaterialsCost::Select('cost')->where('material', '=', $row->material_2)->where('date_import','=',$this->data_material)->first()->cost;
        $row->material_cost = (float)$cost;
        // TODO e se non c'è il materiale cosa si fa???????
        $row->save();
    }

    private function searchMaterial($order_id, $row){
        $matirial = Advisory::all()
            ->where('material_1', '=', $row->material_2)
            ->where('head', '=', $row->head)
            ->first();
        if(empty($matirial->order))
            $this->calcMaterial($row);
        else
            $this->calcBlock($order_id, $row);

    }
}
