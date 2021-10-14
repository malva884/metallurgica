<?php

namespace App\Models;

use App\Http\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use phpDocumentor\Reflection\DocBlock\Tags\Generic;

class WorkStatus extends Model
{
    use HasFactory, UsesUuid;

    protected $fillable = [
        'id', 'head', 'order', 'father', 'material_1', 'material_description_1', 'division', 'total_order_quantity', 'um', 'quantity_1', 'operation_activity',
        'work_center', 'work_center_cost', 'confirmed_quantity', 'activity_machine_hours', 'machine_cost', 'activity_manpower_hours',
        'manpower_cost', 'machine_cost_manpower', 'calculation_1', 'material_description_2', 'requirement_quantity', 'base_unit_measure',
        'quantity_2', 'material_cost', 'cost_material','calculation_2', 'total_raw_material', 'total_machine_cost_manpower', 'final_total',
        'raw_material_machine_manpower_cost', 'material_2', 'date_created', 'created_at', 'updated_at', 'check_row', 'result_1', 'result_2','view',
        'qty_perv_fase', 'um_fase'
    ];

    private $camps = [
        0 => 'order', 1 => 'material_1', 2 => 'material_description_1', 3 => 'division', 4 => 'total_order_quantity', 5 => 'um', 6 => 'quantity_1', 7 => 'operation_activity',
        8 => 'work_center', 'machine_cost' => 'work_center_cost', 10 => 'confirmed_quantity', 11 => 'activity_machine_hours', 'machine_cost_total' => 'machine_cost', 13 => 'activity_manpower_hours',
        'manpower_cost_total' => 'manpower_cost', 'machine_cost_manpower' => 'machine_cost_manpower', 'calculation_1' => 'calculation_1', 15 => 'material_2', 16 => 'material_description_2', 17 => 'requirement_quantity', 18 => 'base_unit_measure',
        19 => 'quantity_2', 20 => 'qty_perv_fase', 21 => 'um_fase', 22 => 'material_cost', 'calculation_2' => 'calculation_2', 23 => 'total_raw_material', 24 => 'total_machine_cost_manpower', 25 => 'final_total',
        'result_1' => 'result_1', 'result_2' => 'result_2',
    ];

#26 => 'raw_material_machine_manpower_cost',

    public function stored($manpower, $materiali,$macchinari,$row, $head, $save_cleck, $father = null, $infoOrder = null)
    {
        try {
            $machine_cost = 0;
            $material_cost = 0;
            foreach ($this->camps as $key => $camp) {
                //Log::channel('stderr')->info('NUMERO: ' . $this->order_row . ' KEY: ' . $key);
                switch ($key) {
                    case 8:
                        if ($row[$key]) {
                            $machine_cost = (!empty( $macchinari[$row[$key]]) ? $macchinari[$row[$key]]:0.00);
                           // $machine_cost = MachineLaborCosts::Select('cost')->where('machine', '=', $row[$key])->where('date_import','=',$data_macchinari)->first()->cost;
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
                            $material_cost = (!empty( $materiali[$row[$key]]) ? $materiali[$row[$key]]:0.00);
                            //$material_cost = MaterialsCost::Select('cost')->where('material', '=', $row[$key])->where('date_import','=',$data_materiali)->first()->cost;
                            $this->cost_material = $material_cost;

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
                            $this->$camp = $manpower * $this->activity_manpower_hours;
                        }
                        break;
                    case 25:
                    case 'calculation_1':
                    case 'calculation_2':

                        break;
                    case 'result_1':
                        if (!empty($this->machine_cost_manpower)) {
                            if ((float)$this->confirmed_quantity == $infoOrder['quantity']) {
                                $this->result_1 = $this->machine_cost_manpower;
                                $this->calculation_1 = 1;
                            } elseif (!empty($infoOrder['coefficiente']) && !empty((float)$this->qty_perv_fase) && ((float)$this->confirmed_quantity > $infoOrder['quantity']) && ((float)$this->confirmed_quantity >= (float)$this->qty_perv_fase)) {
                                $this->result_1 = $infoOrder['coefficiente'] * $this->machine_cost_manpower;
                                $this->calculation_1 = $infoOrder['coefficiente'];
                            } elseif (!empty($infoOrder['coefficiente']) && !empty((float)$this->qty_perv_fase) && (float)$this->confirmed_quantity >= (float)$this->qty_perv_fase) {
                                $this->result_1 = $infoOrder['coefficiente'] * (float)$this->machine_cost_manpower;
                                $this->calculation_1 = $infoOrder['coefficiente'];
                            } else {
                                $this->result_1 = 0.00;

                            }
                            $this->result_1 = round((float)$this->result_1, 2);

                        } else {
                            $this->result_1 = 0.00;
                        }

                        break;
                    case 'result_2':
                        if (!empty($this->material_cost) && !empty($infoOrder['coefficiente'])) {
                            $this->result_2 = round($infoOrder['coefficiente'] * (float)$this->material_cost, 2);
                            if (!empty($infoOrder['coefficiente']))
                                $this->calculation_2 = $infoOrder['coefficiente'];
                        } else {

                            $this->result_2 = 0.00;
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
                if ($this->quantity_1 > 0) {
                    $cal = (((float)$this->total_order_quantity - (float)$this->quantity_1) / (float)$this->total_order_quantity) -1;
                    $cal = str_replace("-", "", $cal);
                    $infoOrder['coefficiente'] = round($cal, 3);
                }
            }
            $this->head = $head->id;

            if ($save_cleck && (float)$this->requirement_quantity > 0 && (float)$this->quantity_2 == 0) {
                $obj_father = WorkStatus::all()
                    ->where('order', '=', $father)
                    ->where('head', '=', $head->id)
                    ->first();
                $obj_father->check_row = true;
                $obj_father->save();
                $this->check_row = true;
            }
            if ($this->machine_cost_manpower) {
                if (!empty($infoOrder['tot_row_machine_manpower'])) {
                    $infoOrder['tot_row_machine_manpower'] = $infoOrder['tot_row_machine_manpower'] + (float)$this->machine_cost_manpower;
                    if(!$save_cleck)
                        $infoOrder['tot_row_machine_manpower2'] = $infoOrder['tot_row_machine_manpower2'] + (float)$this->machine_cost_manpower;
                }else {
                    $infoOrder['tot_row_machine_manpower'] = (float)$this->machine_cost_manpower;
                    if(!$save_cleck)
                        $infoOrder['tot_row_machine_manpower2'] = (float)$this->machine_cost_manpower;
                }
            }


            if ($this->material_cost) {
                if (!empty($infoOrder['tot_row_matirial'])){
                    $infoOrder['tot_row_matirial'] = $infoOrder['tot_row_matirial'] + (float)$this->material_cost;
                    if(!$save_cleck)
                        $infoOrder['tot_row_matirial2'] = $infoOrder['tot_row_matirial2'] + (float)$this->material_cost;
                }
                else{
                    $infoOrder['tot_row_matirial'] = (float)$this->material_cost;
                    if(!$save_cleck)
                        $infoOrder['tot_row_matirial2'] = (float)$this->material_cost;
                }
            }
            $this->view = false;
            if ($save_cleck)
                $this->view = true;
            $this->save();

            return $infoOrder;
        } catch (\Exception $e) {
            //Log::channel('stderr')->info('  QUIIIII!!!! ');
            Log::channel('stderr')->info($e);
            // return redirect(route('users.index'));
        }

    }



}
