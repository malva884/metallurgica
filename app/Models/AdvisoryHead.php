<?php

namespace App\Models;

use App\Http\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class AdvisoryHead extends Model
{
    use HasFactory, UsesUuid;

    protected $fillable = [
        'id', 'status', 'type', 'ol', 'code_product', 'total_real', 'total_theoretical', 'date_material', 'date_macchine'
    ];

    private $lastNumber = 0;
    private $nextBlock = '';
    private $costMacchineOrder = [];
    private $data_macchinari = '';
    private $data_matariali = '';
    private $orderby = '';

    function orderAdvisory()
    {
        if($this->type == 'ot')
            $this->orderby = 'order_row2';
        else
            $this->orderby = 'order_row';

        $mat = Advisory::$matirial_order;
        // trovo il promo blocco
        $order = Advisory::select("*")
            ->whereNotNull('order')
            ->where('head', '=', $this->id)
            ->where('material_1', 'like', $mat[0] . '%')
            ->orWhere('material_1', 'like', $mat[1] . '%')
            ->first();

        if (empty($order->order))
            abort('404');

        $this->get_order_block($order->order);
    }

    /**
     * Recupero l'id del blocco tramite il nome del matariale
     *
     */
    private function get_order_id($meterial)
    {

        $order = Advisory::select("*")
            ->where('head', '=', $this->id)
            ->where('material_1', '=', $meterial)
            ->first();

        $this->get_order_block($order->order);
    }

    /**
     * recupera tutte le righe dell'ordine
     * ordineato per ASC della colonna order_row
     *
     */
    private function get_order_block($order = null)
    {
        if ($order)
            $rows = Advisory::select('*')
                ->where('head', '=', $this->id)
                ->where('order', '=', $order)
                ->orWhere('father', '=', $order)
                ->orderBy('order_row', 'asc')
                ->get();
        else {
            $rows = Advisory::select('*')
                ->where('head', '=', $this->id)
                ->whereNull('order_row2')
                ->orderBy('order_row', 'asc')
                ->get();
        }


        if ($rows->count())
            $this->set_order($rows);
        else {
            $order = Advisory::select('order')
                ->where('head', '=', $this->id)
                ->orderBy($this->orderby, "ASC")
                ->first();
            $this->ol = $order->order;
            $this->save();
            return '0000';
        }

    }


    /**
     * ordina le righe dell'ordine
     * e cerca il blocco successivo
     *
     */
    private function set_order($block)
    {
        $this->nextBlock = null;
        $mat = Advisory::$matirial_order;
        $fase = null;

        foreach ($block as $row) {
            if ($row->order)
                $orderFather = $row;
            if (!empty($row->operation_activity)) {
                $fase = $row->operation_activity;

            }


            $this->lastNumber++;
            $row->order_row2 = $this->lastNumber;
            $row->operation_activity_row = $fase;
            $row->save();
            if (in_array(substr($row->material_2, 0, 2), $mat)) {
                $this->nextBlock = $row->material_2;
                if (substr($row->material_2, 0, 2) == 'BU' || substr($row->material_2, 0, 2) == 'CO')
                    $this->nextBlock = 'BU-CO';
            }
        }
        if ($this->nextBlock != 'BU-CO')
            $this->get_order_id($this->nextBlock);
        else
            $this->get_order_block();
    }

    public function calculateFase($rows)
    {

        $order = null;
        $father = null;
        $fase = '';

        foreach ($rows as $row) {
            if($this->type == 'rm')
                $row->order_row2 = $row->order_row;
            $father = $row->father;
            if (!empty($row->order)) {
                $order = $row->order;
            }

            if (!empty(trim($row->operation_activity))) {
                $fase = trim($row->operation_activity);
                $machine_hour = $row->activity_machine_hours = $this->convertMinutesTohours($row->activity_machine_minutes);
                $manpower_hours = $row->activity_manpower_hours = $this->convertMinutesTohours($row->activity_manpower_minutes);
                $machine_theoretic_hours = $row->activity_machine_theoretic_hours = $this->convertMinutesTohours($row->activity_machine_theoretic_minutes);
                $manpower_theoretic_hours = $row->activity_manpower_theoretic_hours = $this->convertMinutesTohours($row->activity_manpower_theoretic_minutes);
                $macchinaCosto = MachineLaborCosts::Select('cost')->where('machine', '=', $row->work_center)->where('date_import', '=', $this->data_macchinari)->first()->cost;
                $row->work_center_cost = (float)$macchinaCosto;
                $manpower = Greneric::find(1)->manpower;
                $row->manpower_cost_real = round($manpower * $manpower_hours, 2);
                $row->macchine_cost_real = round($row->work_center_cost * $machine_hour, 2);

                $row->manpower_cost_theoretic = round($manpower * $manpower_theoretic_hours, 2);
                $row->machine_cost_theoretic = round((float)$row->work_center_cost * $machine_theoretic_hours, 2);

                $row->machine_cost_manpower = $row->manpower_cost_real + $row->macchine_cost_real;
                $row->macchine_manpower_cost_theoretic = $row->manpower_cost_theoretic + $row->machine_cost_theoretic;

            }
            $materialCheck = false;
            if ($row->material_2) {
                if($this->type == 'ot') {
                    $mat = Advisory::$matirial_order;
                    $advisorySummary = null;
                    if (in_array(substr($row->material_2, 0, 2), $mat)) {
                        $advisorySummary = $this->getTotalMatarial($row);
                        if (!empty($advisorySummary)) {
                            $materialCheck = true;
                            $row->material_real_cost = (float)$advisorySummary;
                        }
                    }
                }

                // if(empty($materialCheck)){
                $material_cost = MaterialsCost::Select('cost')->where('material', '=', $row->material_2)->where('date_import', '=', $this->data_matariali)->first()->cost;
                $row->material_cost = (float)$material_cost;
                $quantity = $row->quantity_2;
                $quantity = (float)str_replace('-', '', $quantity);
                if (empty($materialCheck)) {
                    if ($quantity > 0)
                        $material = round($row->material_cost * $quantity, 2);
                    else {
                        $material = round($row->material_cost * (float)$row->requirement_quantity, 2);
                        $row->check_row = true;
                        $this->setCheckOrder($row->father);
                    }
                    $row->material_real_cost = $material;
                }
                $row->material_theoretic_cost = round((float)$row->material_cost * (float)$row->requirement_quantity, 2);
                //}
            }

            $row->calculated = true;
            $row->save();
        }

        if (empty($order))
            $order = $father;
        $this->calculateToalFase($order, $fase);
    }

    public function getBlockCalculation()
    {

        if (!$this->data_macchinari && !$this->data_matariali) {
            $this->data_macchinari = $this->date_macchine;
            $this->data_matariali = $this->date_material;
        }

        if ($this->type == 'ot') {
            $this->orderby = 'order_row2';
            $orderBlock = Advisory::Select('*')
                ->where('head', 'like', $this->id)
                ->where('order', '=', '')
                ->where('calculated', '=', 0)
                ->orderBy("order_row2", "DESC")
                ->first();

            if (!empty($orderBlock->id)) {
                $order = (!empty($orderBlock->order) ? $orderBlock->order : $orderBlock->father);
                $orderFasi = Advisory::Select('operation_activity')
                    ->where('head', 'like', $this->id)
                    ->whereNotNull('operation_activity')
                    ->where('calculated', '=', 0)
                    ->where('father', '=', $order)
                    ->orderBy("order_row2", "asc")
                    ->get();

                foreach ($orderFasi as $orderFase) {
                    $advisoryBlock = Advisory::Select('*')
                        ->where('head', '=', $this->id)
                        ->where(function ($query) use ($order) {
                            $query->where('order', '=', $order)
                                ->orWhere('father', '=', $order);
                        })
                        ->where(function ($query) use ($orderFase) {
                            $query->where('operation_activity', '=', $orderFase->operation_activity)
                                ->orWhere('operation_activity_row', '=', $orderFase->operation_activity);
                        })
                        ->orderBy("order_row2", "ASC")
                        ->get();

                    if ($advisoryBlock->count())
                        $this->calculateFase($advisoryBlock);
                }
            } else {
                return null;
            }
        }else{
            $this->orderby = 'order_row';
            $orderBlock = Advisory::Select('*')
                ->where('head', 'like', $this->id)
                ->where('order', '<>','')
                ->where('calculated', '=', 0)
                ->orderBy("order_row", "asc")
                ->first();

            if ($orderBlock->id){
                $orderFasi = Advisory::Select('operation_activity','calculated')
                    ->where('head', 'like', $this->id)
                    ->whereNotNull('operation_activity')
                    ->where('calculated', '=', 0)
                    ->where('father', '=', $orderBlock->order)
                    ->orderBy("order_row2", "asc")
                    ->get();

                foreach ($orderFasi as $orderFase) {
                    $advisoryBlock = Advisory::Select('*')
                        ->where('head', '=', $this->id)
                        ->where('calculated', '=', 0)
                        ->where(function ($query) use ( $orderBlock) {
                            $query->where('order', '=', $orderBlock->order)
                                ->orWhere('father', '=', $orderBlock->order);
                        })
                        ->where(function ($query) use ($orderFase) {
                            $query->where('operation_activity', '=', $orderFase->operation_activity)
                                ->orWhere('operation_activity_row', '=', $orderFase->operation_activity);
                        })
                        ->orderBy("order_row", "ASC")
                        ->get();

                    if ($advisoryBlock->count())
                        $this->calculateFase($advisoryBlock);
                }
            }
        }
    }

    private function convertMinutesTohours($minutes)
    {
        return round((float)$minutes / 60, 2);
    }

    private function setCheckOrder($order)
    {
        $order = Advisory::all()
            ->where('order', '=', $order)
            ->where('head', '=', $this->id)
            ->first();
        $order->check_row = true;
        $order->save();
    }

    public function calculateToalFase($order, $fase)
    {
        $ordersHead = Advisory::Select('*')
            ->where('order', '=', $order)
            ->first();

        $ordersFase = Advisory::Select('*')
            ->where('order', '=', $order)
            ->orWhere('father', '=', $order)
            ->where('head', '=', $this->id)
            ->where(function ($query) use ($fase) {
                $query->where('operation_activity', '=', $fase)
                    ->orWhere('operation_activity_row', '=', $fase);
            })
            ->orderBy($this->orderby, "ASC")
            ->get();

        $total_real = 0.00;
        $total_theoretic = 0.00;
        $costoManodoperaMachinaReale = 0.00;
        $costoManodoperaMachinaTeorico = 0.00;

        foreach ($ordersFase as $row) {
            if ($row->operation_activity) {
                $costoManodoperaMachinaReale = (float)$row->machine_cost_manpower;
                $costoManodoperaMachinaTeorico = $row->macchine_manpower_cost_theoretic;
            } elseif (!$row->operation_activity && !$row->order) {
                $total_real = $total_real + $row->material_real_cost;
                $total_theoretic = $total_theoretic + $row->material_theoretic_cost;
            }

        }


        $total_real = round($total_real + $costoManodoperaMachinaReale, 2);
        $total_theoretic = round($total_theoretic + $costoManodoperaMachinaTeorico, 2);
        $summary = AdvisorySummary::all()->where('order', '=', $order)->where('head', '=', $this->id)->first();

        if (empty($summary->id)) {
            $summary = new AdvisorySummary;
            $summary->order = $order;
            $summary->head = $this->id;
            $summary->total_real = $total_real;
            $summary->total_theoretical = $total_theoretic;
            $summary->cost_material = round($total_real / $ordersHead->quantity_1, 2);
            $summary->quantity_produced = $ordersHead->total_order_quantity;
        } else {
            $summary->total_real = $summary->total_real + $total_real;
            $summary->total_theoretical = $summary->total_theoretical + $total_theoretic;
            $summary->cost_material = round(($total_real / $ordersHead->quantity_1) + $summary->cost_material, 2);
        }

        $summary->save();

        $this->getBlockCalculation();
    }

    private function getTotalMatarial($row)
    {

        $advisory = Advisory::select('order', 'total_order_quantity')
            ->where('material_1', '=', $row->material_2)
            ->where('head', '=', $this->id)
            ->first();

        if (!empty($advisory->order)) {
            $advisorySummary = AdvisorySummary::select('*')
                ->where('order', '=', $advisory->order)
                ->where('head', '=', $this->id)
                ->first();
            $row->quantity_2 = str_replace("-", "", $row->quantity_2);
            if ($row->quantity_2 < $advisory->total_order_quantity) {
                return $advisorySummary->cost_material * $row->quantity_2;
            } else
                return $advisorySummary->total_real;
        }
        return null;
    }


}
