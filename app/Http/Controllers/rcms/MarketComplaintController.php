<?php

namespace App\Http\Controllers\rcms;

use App\Http\Controllers\Controller;
use App\Models\MarketComplaint;
use App\Models\MarketComplaintGrids ;
use App\Models\MarketComplaintAuditTrial;
use App\Models\Capa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\RecordNumber;
use App\Models\RoleGroup;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\App;
use Helpers;

use Carbon\Carbon;
use PDF;
class MarketComplaintController extends Controller
{
    public function index()
    {
        $record = ((RecordNumber::first()->value('counter')) + 1);
        $record = str_pad($record, 4, '0', STR_PAD_LEFT);
        $currentDate = Carbon::now();
        $formattedDate = $currentDate->addDays(30);
        $due_date = $formattedDate->format('Y-m-d');
        

        return view('frontend.market_complaint.market_complaint_new',compact('due_date', 'record'));
    }


    public function store(Request $request)
    {



        if (!$request->description_gi) {
            toastr()->info("Short Description is required");
            return redirect()->back()->withInput();
        }
        $marketComplaint = new MarketComplaint();


        $marketComplaint->status = 'Opened';
        $marketComplaint->stage = 1;

        // Manually assigning each field from the request
        $marketComplaint->initiator_id = Auth::user()->id;
        $marketComplaint->division_id = $request->division_id;
        $marketComplaint->initiator_group = $request->initiator_group;
        $marketComplaint->intiation_date = $request->intiation_date;
        $marketComplaint->due_date_gi = $request->due_date_gi;
        $marketComplaint->initiator_group_code_gi = $request->initiator_group_code_gi;
        $marketComplaint->record =((RecordNumber::first()->value('counter')) + 1);
        $marketComplaint->initiated_through_gi = $request->initiated_through_gi;
        $marketComplaint->if_other_gi = $request->if_other_gi;
        $marketComplaint->is_repeat_gi = $request->is_repeat_gi;
        $marketComplaint->repeat_nature_gi = $request->repeat_nature_gi;
        $marketComplaint->description_gi = $request->description_gi;
        // $marketComplaint->initial_attachment_gi = $request->initial_attachment_gi;
        $marketComplaint->complainant_gi = $request->complainant_gi;
        $marketComplaint->complaint_reported_on_gi = $request->complaint_reported_on_gi;
        $marketComplaint->details_of_nature_market_complaint_gi = $request->details_of_nature_market_complaint_gi;
        $marketComplaint->categorization_of_complaint_gi = $request->categorization_of_complaint_gi;
        $marketComplaint->review_of_complaint_sample_gi = $request->review_of_complaint_sample_gi;
        $marketComplaint->review_of_control_sample_gi = $request->review_of_control_sample_gi;
        $marketComplaint->review_of_batch_manufacturing_record_BMR_gi = $request->review_of_batch_manufacturing_record_BMR_gi;
        $marketComplaint->review_of_raw_materials_used_in_batch_manufacturing_gi = $request->review_of_raw_materials_used_in_batch_manufacturing_gi;
        $marketComplaint->review_of_Batch_Packing_record_bpr_gi = $request->review_of_Batch_Packing_record_bpr_gi;
        $marketComplaint->review_of_packing_materials_used_in_batch_packing_gi = $request->review_of_packing_materials_used_in_batch_packing_gi;
        $marketComplaint->review_of_analytical_data_gi = $request->review_of_analytical_data_gi;
        $marketComplaint->review_of_training_record_of_concern_persons_gi = $request->review_of_training_record_of_concern_persons_gi;
        $marketComplaint->rev_eq_inst_qual_calib_record_gi = $request->rev_eq_inst_qual_calib_record_gi;
        $marketComplaint->review_of_equipment_break_down_and_maintainance_record_gi = $request->review_of_equipment_break_down_and_maintainance_record_gi;
        $marketComplaint->review_of_past_history_of_product_gi = $request->review_of_past_history_of_product_gi;
        $marketComplaint->conclusion_hodsr = $request->conclusion_hodsr;
        $marketComplaint->root_cause_analysis_hodsr = $request->root_cause_analysis_hodsr;
        $marketComplaint->probable_root_causes_complaint_hodsr = $request->probable_root_causes_complaint_hodsr;
        $marketComplaint->impact_assessment_hodsr = $request->impact_assessment_hodsr;
        $marketComplaint->corrective_action_hodsr = $request->corrective_action_hodsr;
        $marketComplaint->preventive_action_hodsr = $request->preventive_action_hodsr;
        $marketComplaint->summary_and_conclusion_hodsr = $request->summary_and_conclusion_hodsr;
        // $marketComplaint->initial_attachment_hodsr = $request->initial_attachment_hodsr;
        $marketComplaint->comments_if_any_hodsr = $request->comments_if_any_hodsr;

        $marketComplaint->manufacturer_name_address_ca = $request->manufacturer_name_address_ca;
        $marketComplaint->complaint_sample_required_ca = $request->complaint_sample_required_ca;
        $marketComplaint->complaint_sample_status_ca = $request->complaint_sample_status_ca;
        $marketComplaint->brief_description_of_complaint_ca = $request->brief_description_of_complaint_ca;
        $marketComplaint->batch_record_review_observation_ca = $request->batch_record_review_observation_ca;
        $marketComplaint->analytical_data_review_observation_ca = $request->analytical_data_review_observation_ca;
        $marketComplaint->retention_sample_review_observation_ca = $request->retention_sample_review_observation_ca;
        $marketComplaint->stability_study_data_review_ca = $request->stability_study_data_review_ca;
        $marketComplaint->qms_events_ifany_review_observation_ca = $request->qms_events_ifany_review_observation_ca;
        $marketComplaint->repeated_complaints_queries_for_product_ca = $request->repeated_complaints_queries_for_product_ca;
        $marketComplaint->interpretation_on_complaint_sample_ifrecieved_ca = $request->interpretation_on_complaint_sample_ifrecieved_ca;
        $marketComplaint->comments_ifany_ca = $request->comments_ifany_ca;
        // $marketComplaint->initial_attachment_ca = $request->initial_attachment_ca;

        // Closure section
        $marketComplaint->closure_comment_c = $request->closure_comment_c;
        // $marketComplaint->initial_attachment_c = $request->initial_attachment_c;



        //  dd($marketComplaint->record);
            $marketComplaint->form_type="Market Complaint";
      
            // {{----.File attachemenet   }}


            // if (!empty($request->initial_attachment_gi)) {
            //     $files = [];
            //     if ($request->hasfile('initial_attachment_gi')) {
            //         foreach ($request->file('initial_attachment_gi') as $file) {
            //             $name = $request->name . 'initial_attachment_gi' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
            //             $file->move('upload/', $name);
            //             $files[] = $name;
            //         }
            //     }
            //     $marketComplaint->initial_attachment_gi = json_encode($files);
            // }
            if (!empty($request->initial_attachment_gi)) {
                $files = [];
                if ($request->hasFile('initial_attachment_gi')) {
                    foreach ($request->file('initial_attachment_gi') as $file) {
                        // Generate a unique name for the file
                        $name = $request->name . '_initial_attachment_gi_' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                        
                        // Move the file to the upload directory
                        $file->move(public_path('upload/'), $name);
                        
                        // Add the file name to the array
                        $files[] = $name;
                    }
                }
                // Encode the file names array to JSON and assign it to the model
                $marketComplaint->initial_attachment_gi = json_encode($files);
            }


            if (!empty($request->initial_attachment_hodsr)) {
                $files = [];
                if ($request->hasfile('initial_attachment_hodsr')) {
                    foreach ($request->file('initial_attachment_hodsr') as $file) {
                        $name = $request->name . 'initial_attachment_hodsr' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                        $file->move('upload/', $name);
                        $files[] = $name;
                    }
                }
                $marketComplaint->initial_attachment_hodsr = json_encode($files);
            }


            if (!empty($request->initial_attachment_ca)) {
                $files = [];
                if ($request->hasfile('initial_attachment_ca')) {
                    foreach ($request->file('initial_attachment_ca') as $file) {
                        $name = $request->name . 'initial_attachment_ca' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                        $file->move('upload/', $name);
                        $files[] = $name;
                    }
                }
                $marketComplaint->initial_attachment_ca = json_encode($files);
            }
            if (!empty($request->initial_attachment_c)) {
                $files = [];
                if ($request->hasfile('initial_attachment_c')) {
                    foreach ($request->file('initial_attachment_c') as $file) {
                        $name = $request->name . 'initial_attachment_c' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                        $file->move('upload/', $name);
                        $files[] = $name;
                    }
                }
                $marketComplaint->initial_attachment_c = json_encode($files);
            }
            // dd($marketComplaint);




            $marketComplaint->save();




            $record = RecordNumber::first();
            $record->counter = ((RecordNumber::first()->value('counter')) + 1);
            $record->update();


            // ----------------------------------autid show  fileds ----------------------------------------------------------
          

            if (!empty($marketComplaint->description_gi)) {
                $history = new MarketComplaintAuditTrial();
                $history->market_id = $marketComplaint->id;
                $history->activity_type = 'Description';
                $history->previous = "NA";
                $history->current = $marketComplaint->description_gi;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $marketComplaint->status;
                $history->change_to = "Opened";
                $history->change_from = "Initiator";
                $history->action_name = "store";
                $history->save();
            }

            if (!empty($marketComplaint->initiator_group)) {
                $history = new MarketComplaintAuditTrial();
                $history->market_id = $marketComplaint->id;
                $history->activity_type = 'Initiator Group';
                $history->previous = "NA";
                $history->current = $marketComplaint->initiator_group;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $marketComplaint->status;
                 $history->change_to = "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = "store";
                $history->save();
            }

              if (!empty($marketComplaint->initiated_through_gi)) {
                $history = new MarketComplaintAuditTrial();
                $history->market_id = $marketComplaint->id;
                $history->activity_type = 'Initiated Through';
                $history->previous = "NA";
                $history->current = $marketComplaint->initiated_through_gi;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $marketComplaint->status;
                 $history->change_to = "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = "store";
                $history->save();
            }

            if (!empty($marketComplaint->if_other_gi)) {
                $history = new MarketComplaintAuditTrial();
                $history->market_id = $marketComplaint->id;
                $history->activity_type = 'If Other';
                $history->previous = "NA";
                $history->current = $marketComplaint->if_other_gi;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $marketComplaint->status;
                 $history->change_to = "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = "store";
                $history->save();
            }

            if (!empty($marketComplaint->is_repeat_gi)) {
                $history = new MarketComplaintAuditTrial();
                $history->market_id = $marketComplaint->id;
                $history->activity_type = 'Is Repeat';
                $history->previous = "NA";
                $history->current = $marketComplaint->is_repeat_gi;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $marketComplaint->status;
                 $history->change_to = "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = "store";
                $history->save();
            }

            if (!empty($marketComplaint->repeat_nature_gi)) {
                $history = new MarketComplaintAuditTrial();
                $history->market_id = $marketComplaint->id;
                $history->activity_type = 'Repeat Nature';
                $history->previous = "NA";
                $history->current = $marketComplaint->repeat_nature_gi;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $marketComplaint->status;
                 $history->change_to = "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = "store";
                $history->save();
            }

            if (!empty($marketComplaint->initial_attachment_gi)) {
                $history = new MarketComplaintAuditTrial();
                $history->market_id = $marketComplaint->id;
                $history->activity_type = 'Information Attachment';
                $history->previous = "NA";
                $history->current = $marketComplaint->initial_attachment_gi;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $marketComplaint->status;
                 $history->change_to = "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = "store";
                $history->save();
            }


            if (!empty($marketComplaint->complainant_gi)) {
                $history = new MarketComplaintAuditTrial();
                $history->market_id = $marketComplaint->id;
                $history->activity_type = 'Complainant';
                $history->previous = "NA";
                $history->current = $marketComplaint->complainant_gi;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $marketComplaint->status;
                 $history->change_to = "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = "store";
                $history->save();
            }

            if (!empty($marketComplaint->complaint_reported_on_gi)) {
                $history = new MarketComplaintAuditTrial();
                $history->market_id = $marketComplaint->id;
                $history->activity_type = 'Complaint Reported On';
                $history->previous = "NA";
                $history->current = $marketComplaint->complaint_reported_on_gi;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $marketComplaint->status;
                 $history->change_to = "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = "store";
                $history->save();
            }

            if (!empty($marketComplaint->details_of_nature_market_complaint_gi)) {
                $history = new MarketComplaintAuditTrial();
                $history->market_id = $marketComplaint->id;
                $history->activity_type = 'Details Of Nature Market Complaint';
                $history->previous = "NA";
                $history->current = $marketComplaint->details_of_nature_market_complaint_gi;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $marketComplaint->status;
                 $history->change_to = "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = "store";
                $history->save();
            }

            if (!empty($marketComplaint->categorization_of_complaint_gi)) {
                $history = new MarketComplaintAuditTrial();
                $history->market_id = $marketComplaint->id;
                $history->activity_type = 'Categorization of complaint';
                $history->previous = "NA";
                $history->current = $marketComplaint->categorization_of_complaint_gi;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $marketComplaint->status;
                 $history->change_to = "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = "store";
                $history->save();
            }

            if (!empty($marketComplaint->review_of_complaint_sample_gi)) {
                $history = new MarketComplaintAuditTrial();
                $history->market_id = $marketComplaint->id;
                $history->activity_type = 'Review of Complaint Samplet';
                $history->previous = "NA";
                $history->current = $marketComplaint->review_of_complaint_sample_gi;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $marketComplaint->status;
                 $history->change_to = "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = "store";
                $history->save();
            }

            if (!empty($marketComplaint->review_of_past_history_of_product_gi)) {
                $history = new MarketComplaintAuditTrial();
                $history->market_id = $marketComplaint->id;
                $history->activity_type = 'Review of Past history of product';
                $history->previous = "NA";
                $history->current = $marketComplaint->review_of_past_history_of_product_gi;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $marketComplaint->status;
                $history->change_to = "Opened";
                $history->change_from = "Initiator";
                $history->action_name = "store";

                $history->save();
            }
            if (!empty($marketComplaint->review_of_equipment_break_down_and_maintainance_record_gi)) {
                $history = new MarketComplaintAuditTrial();
                $history->market_id = $marketComplaint->id;
                $history->activity_type = 'Review of Equipment Break-down and Maintainance Record';
                $history->previous = "NA";
                $history->current = $marketComplaint->review_of_equipment_break_down_and_maintainance_record_gi;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $marketComplaint->status;
                 $history->change_to = "Opened";
                $history->change_from = "Initiator";
                $history->action_name = "store";
                $history->save();
            }
            if (!empty($marketComplaint->rev_eq_inst_qual_calib_record_gi)) {
                $history = new MarketComplaintAuditTrial();
                $history->market_id = $marketComplaint->id;
                $history->activity_type = 'Review of Equipment Instrument qualification Calibration record';
                $history->previous = "NA";
                $history->current = $marketComplaint->rev_eq_inst_qual_calib_record_gi;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $marketComplaint->status;
                 $history->change_to = "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = "store";
                $history->save();
            }

            if (!empty($marketComplaint->review_of_training_record_of_concern_persons_gi)) {
                $history = new MarketComplaintAuditTrial();
                $history->market_id = $marketComplaint->id;
                $history->activity_type = 'Review of training record of Concern Persons';
                $history->previous = "NA";
                $history->current = $marketComplaint->review_of_training_record_of_concern_persons_gi;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $marketComplaint->status;
                 $history->change_to = "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = "store";
                $history->save();
            }
            if (!empty($marketComplaint->review_of_analytical_data_gi)) {
                $history = new MarketComplaintAuditTrial();
                $history->market_id = $marketComplaint->id;
                $history->activity_type = 'Review of Analytical Data';
                $history->previous = "NA";
                $history->current = $marketComplaint->review_of_analytical_data_gi;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $marketComplaint->status;
                 $history->change_to = "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = "store";
                $history->save();
            }

            if (!empty($marketComplaint->review_of_raw_materials_used_in_batch_manufacturing_gi)) {
                $history = new MarketComplaintAuditTrial();
                $history->market_id = $marketComplaint->id;
                $history->activity_type = 'Review of packing materials used in batch packing';
                $history->previous = "NA";
                $history->current = $marketComplaint->review_of_raw_materials_used_in_batch_manufacturing_gi;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $marketComplaint->status;
                 $history->change_to = "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = "store";
                $history->save();
            }
            if (!empty($marketComplaint->review_of_raw_materials_used_in_batch_manufacturing_gi)) {
                $history = new MarketComplaintAuditTrial();
                $history->market_id = $marketComplaint->id;
                $history->activity_type = 'Review of Raw materials used in batch man';
                $history->previous = "NA";
                $history->current = $marketComplaint->review_of_raw_materials_used_in_batch_manufacturing_gi;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $marketComplaint->status;
                 $history->change_to = "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = "store";
                $history->save();
            }
            if (!empty($marketComplaint->review_of_Batch_Packing_record_bpr_gi)) {
                $history = new MarketComplaintAuditTrial();
                $history->market_id = $marketComplaint->id;
                $history->activity_type = 'Review of Batch Packing record BPR';
                $history->previous = "NA";
                $history->current = $marketComplaint->review_of_Batch_Packing_record_bpr_gi;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $marketComplaint->status;
                 $history->change_to = "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = "store";
                $history->save();
            }


            if (!empty($marketComplaint->review_of_control_sample_gi)) {
                $history = new MarketComplaintAuditTrial();
                $history->market_id = $marketComplaint->id;
                $history->activity_type = 'Review of Control Sample';
                $history->previous = "NA";
                $history->current = $marketComplaint->review_of_control_sample_gi;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $marketComplaint->status;
                 $history->change_to = "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = "store";
                $history->save();
            }

            if (!empty($marketComplaint->review_of_batch_manufacturing_record_BMR_gi)) {
                $history = new MarketComplaintAuditTrial();
                $history->market_id = $marketComplaint->id;
                $history->activity_type = 'Review of Batch manufacturing record';
                $history->previous = "NA";
                $history->current = $marketComplaint->review_of_batch_manufacturing_record_BMR_gi;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $marketComplaint->status;
                 $history->change_to = "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = "store";
                $history->save();
            }
            if (!empty($marketComplaint->conclusion_hodsr)) {
                $history = new MarketComplaintAuditTrial();
                $history->market_id = $marketComplaint->id;
                $history->activity_type = 'Review of Complaint Samplet';
                $history->previous = "NA";
                $history->current = $marketComplaint->conclusion_hodsr;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $marketComplaint->status;
                 $history->change_to = "Opened";
                $history->change_from = "Initiator";
                $history->action_name = "store";
                $history->save();
            }

            if (!empty($marketComplaint->root_cause_analysis_hodsr)) {
                $history = new MarketComplaintAuditTrial();
                $history->market_id = $marketComplaint->id;
                $history->activity_type = 'Root Cause Analysis';
                $history->previous = "NA";
                $history->current = $marketComplaint->root_cause_analysis_hodsr;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $marketComplaint->status;
                 $history->change_to = "Opened";
                $history->change_from = "Initiator";
                $history->action_name = "store";
                $history->save();
            }

            if (!empty($marketComplaint->probable_root_causes_complaint_hodsr)) {
                $history = new MarketComplaintAuditTrial();
                $history->market_id = $marketComplaint->id;
                $history->activity_type = 'The most probable root causes identified of the complaint are as below';
                $history->previous = "NA";
                $history->current = $marketComplaint->probable_root_causes_complaint_hodsr;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $marketComplaint->status;
                 $history->change_to = "Opened";
                $history->change_from = "Initiator";
                $history->action_name = "store";
                $history->save();
            }

            if (!empty($marketComplaint->impact_assessment_hodsr)) {
                $history = new MarketComplaintAuditTrial();
                $history->market_id = $marketComplaint->id;
                $history->activity_type = 'Impact Assessment';
                $history->previous = "NA";
                $history->current = $marketComplaint->impact_assessment_hodsr;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $marketComplaint->status;
                 $history->change_to = "Opened";
                $history->change_from = "Initiator";
                $history->action_name = "store";
                $history->save();
            }

            if (!empty($marketComplaint->corrective_action_hodsr)) {
                $history = new MarketComplaintAuditTrial();
                $history->market_id = $marketComplaint->id;
                $history->activity_type = 'Corrective Action';
                $history->previous = "NA";
                $history->current = $marketComplaint->corrective_action_hodsr;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $marketComplaint->status;
                 $history->change_to = "Opened";
                $history->change_from = "Initiator";
                $history->action_name = "store";
                $history->save();
            }

            if (!empty($marketComplaint->preventive_action_hodsr)) {
                $history = new MarketComplaintAuditTrial();
                $history->market_id = $marketComplaint->id;
                $history->activity_type = 'Preventive Action';
                $history->previous = "NA";
                $history->current = $marketComplaint->preventive_action_hodsr;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $marketComplaint->status;
                 $history->change_to = "Opened";
                $history->change_from = "Initiator";
                $history->action_name = "store";
                $history->save();
            }

            if (!empty($marketComplaint->summary_and_conclusion_hodsr)) {
                $history = new MarketComplaintAuditTrial();
                $history->market_id = $marketComplaint->id;
                $history->activity_type = 'Summary and Conclusion';
                $history->previous = "NA";
                $history->current = $marketComplaint->summary_and_conclusion_hodsr;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $marketComplaint->status;
                 $history->change_to = "Opened";
                $history->change_from = "Initiator";
                $history->action_name = "store";
                $history->save();
            }

            if (!empty($marketComplaint->initial_attachment_hodsr)) {
                $history = new MarketComplaintAuditTrial();
                $history->market_id = $marketComplaint->id;
                $history->activity_type = 'HOD Attachment';
                $history->previous = "NA";
                $history->current = $marketComplaint->initial_attachment_hodsr;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $marketComplaint->status;
                 $history->change_to = "Opened";
                $history->change_from = "Initiator";
                $history->action_name = "store";
                $history->save();
            }

            if (!empty($marketComplaint->comments_if_any_hodsr)) {
                $history = new MarketComplaintAuditTrial();
                $history->market_id = $marketComplaint->id;
                $history->activity_type = 'Comments if any';
                $history->previous = "NA";
                $history->current = $marketComplaint->comments_if_any_hodsr;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $marketComplaint->status;
                 $history->change_to = "Opened";
                $history->change_from = "Initiator";
                $history->action_name = "store";
                $history->save();
            }


            if (!empty($marketComplaint->manufacturer_name_address_ca)) {
                $history = new MarketComplaintAuditTrial();
                $history->market_id = $marketComplaint->id;
                $history->activity_type = 'Manufacturer name & Address';
                $history->previous = "NA";
                $history->current = $marketComplaint->manufacturer_name_address_ca;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $marketComplaint->status;
                 $history->change_to = "Opened";
                $history->change_from = "Initiator";
                $history->action_name = "store";
                $history->save();
            }
            if (!empty($marketComplaint->complaint_sample_required_ca)) {
                $history = new MarketComplaintAuditTrial();
                $history->market_id = $marketComplaint->id;
                $history->activity_type = 'Complaint Sample Required';
                $history->previous = "NA";
                $history->current = $marketComplaint->complaint_sample_required_ca;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $marketComplaint->status;
                 $history->change_to = "Opened";
                $history->change_from = "Initiator";
                $history->action_name = "store";
                $history->save();
            }
            if (!empty($marketComplaint->complaint_sample_status_ca)) {
                $history = new MarketComplaintAuditTrial();
                $history->market_id = $marketComplaint->id;
                $history->activity_type = 'Complaint Sample Required';
                $history->previous = "NA";
                $history->current = $marketComplaint->complaint_sample_status_ca;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $marketComplaint->status;
                 $history->change_to = "Opened";
                $history->change_from = "Initiator";
                $history->action_name = "store";
                $history->save();
            }
            if (!empty($marketComplaint->brief_description_of_complaint_ca)) {
                $history = new MarketComplaintAuditTrial();
                $history->market_id = $marketComplaint->id;
                $history->activity_type = 'Brief Description of complaint';
                $history->previous = "NA";
                $history->current = $marketComplaint->brief_description_of_complaint_ca;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $marketComplaint->status;
                 $history->change_to = "Opened";
                $history->change_from = "Initiator";
                $history->action_name = "store";
                $history->save();
            }
            if (!empty($marketComplaint->batch_record_review_observation_ca)) {
                $history = new MarketComplaintAuditTrial();
                $history->market_id = $marketComplaint->id;
                $history->activity_type = 'Batch Record review observation';
                $history->previous = "NA";
                $history->current = $marketComplaint->batch_record_review_observation_ca;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $marketComplaint->status;
                 $history->change_to = "Opened";
                $history->change_from = "Initiator";
                $history->action_name = "store";
                $history->save();
            }

            if (!empty($marketComplaint->analytical_data_review_observation_ca)) {
                $history = new MarketComplaintAuditTrial();
                $history->market_id = $marketComplaint->id;
                $history->activity_type = 'Analytical Data review observation';
                $history->previous = "NA";
                $history->current = $marketComplaint->analytical_data_review_observation_ca;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $marketComplaint->status;
                 $history->change_to = "Opened";
                $history->change_from = "Initiator";
                $history->action_name = "store";
                $history->save();
            }
            if (!empty($marketComplaint->retention_sample_review_observation_ca)) {
                $history = new MarketComplaintAuditTrial();
                $history->market_id = $marketComplaint->id;
                $history->activity_type = 'Retention sample review observation';
                $history->previous = "NA";
                $history->current = $marketComplaint->retention_sample_review_observation_ca;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $marketComplaint->status;
                 $history->change_to = "Opened";
                $history->change_from = "Initiator";
                $history->action_name = "store";
                $history->save();
            }
            if (!empty($marketComplaint->retention_sample_review_observation_ca)) {
                $history = new MarketComplaintAuditTrial();
                $history->market_id = $marketComplaint->id;
                $history->activity_type = 'Retention sample review observation';
                $history->previous = "NA";
                $history->current = $marketComplaint->retention_sample_review_observation_ca;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $marketComplaint->status;
                 $history->change_to = "Opened";
                $history->change_from = "Initiator";
                $history->action_name = "store";
                $history->save();
            }
            if (!empty($marketComplaint->stability_study_data_review_ca)) {
                $history = new MarketComplaintAuditTrial();
                $history->market_id = $marketComplaint->id;
                $history->activity_type = 'Stablity study data review';
                $history->previous = "NA";
                $history->current = $marketComplaint->stability_study_data_review_ca;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $marketComplaint->status;
                 $history->change_to = "Opened";
                $history->change_from = "Initiator";
                $history->action_name = "store";
                $history->save();
            }
            if (!empty($marketComplaint->qms_events_ifany_review_observation_ca)) {
                $history = new MarketComplaintAuditTrial();
                $history->market_id = $marketComplaint->id;
                $history->activity_type = 'QMS Events(if any) review Observation';
                $history->previous = "NA";
                $history->current = $marketComplaint->qms_events_ifany_review_observation_ca;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $marketComplaint->status;
                 $history->change_to = "Opened";
                $history->change_from = "Initiator";
                $history->action_name = "store";
                $history->save();
            }
            if (!empty($marketComplaint->repeated_complaints_queries_for_product_ca)) {
                $history = new MarketComplaintAuditTrial();
                $history->market_id = $marketComplaint->id;
                $history->activity_type = 'Repeated complaints/queries for product';
                $history->previous = "NA";
                $history->current = $marketComplaint->repeated_complaints_queries_for_product_ca;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $marketComplaint->status;
                 $history->change_to = "Opened";
                $history->change_from = "Initiator";
                $history->action_name = "store";
                $history->save();
            }
            if (!empty($marketComplaint->interpretation_on_complaint_sample_ifrecieved_ca)) {
                $history = new MarketComplaintAuditTrial();
                $history->market_id = $marketComplaint->id;
                $history->activity_type = 'Interpretation on compalint sample(if recieved)';
                $history->previous = "NA";
                $history->current = $marketComplaint->interpretation_on_complaint_sample_ifrecieved_ca;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $marketComplaint->status;
                 $history->change_to = "Opened";
                $history->change_from = "Initiator";
                $history->action_name = "store";
                $history->save();
            }
            if (!empty($marketComplaint->comments_ifany_ca)) {
                $history = new MarketComplaintAuditTrial();
                $history->market_id = $marketComplaint->id;
                $history->activity_type = 'Comments(if Any)';
                $history->previous = "NA";
                $history->current = $marketComplaint->comments_ifany_ca;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $marketComplaint->status;
                 $history->change_to = "Opened";
                $history->change_from = "Initiator";
                $history->action_name = "store";
                $history->save();
            }
            if (!empty($marketComplaint->initial_attachment_ca)) {
                $history = new MarketComplaintAuditTrial();
                $history->market_id = $marketComplaint->id;
                $history->activity_type = 'Acknowledgement Attachment';
                $history->previous = "NA";
                $history->current = $marketComplaint->initial_attachment_ca;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $marketComplaint->status;
                 $history->change_to = "Opened";
                $history->change_from = "Initiator";
                $history->action_name = "store";
                $history->save();
            }


// ====================================================audit show end creatre ========================================
                // -----------------------------------------------------grid storing data


            // For "Product Details"
            $griddata = $marketComplaint->id;

            $product = MarketComplaintGrids::where(['mc_id' => $griddata, 'identifer' => 'ProductDetails'])->firstOrNew();
            $product->mc_id = $griddata;
            $product->identifer = 'ProductDetails';
            $product->data = $request->serial_number_gi;
            $product->save();
        // dd($marketrproducts->data);
        //Traceability
                    // $griddata = $marketComplaint->id;

            $gitracebilty = MarketComplaintGrids::where(['mc_id' => $griddata, 'identifer' => 'Traceability'])->firstOrNew();
            $gitracebilty->mc_id = $griddata;
            $gitracebilty->identifer = 'Traceability';
            $gitracebilty->data = $request->trace_ability;
            $gitracebilty->save();

                // {{-- Investing_team --}}
            $giinvesting = MarketComplaintGrids::where(['mc_id' => $griddata, 'identifer' => 'Investing_team'])->firstOrNew();
            $giinvesting->mc_id = $griddata;
            $giinvesting->identifer = 'Investing_team';
            $giinvesting->data = $request->Investing_team;
            // dd($giinvesting);
            $giinvesting->save();
                // {{-- Brain stroming Session --}}

            $brain = MarketComplaintGrids::where(['mc_id' => $griddata, 'identifer' => 'brain_stroming_details'])->firstOrNew();
            $brain->mc_id = $griddata;
            $brain->identifer = 'brain_stroming_details';
            $brain->data = $request->brain_stroming_details;
            $brain->save();
                // {{ Team Members }}
            $hodteammembers = MarketComplaintGrids::where(['mc_id' => $griddata, 'identifer' => 'Team_Members'])->firstOrNew();
            $hodteammembers->mc_id = $griddata;
            $hodteammembers->identifer = 'Team_Members';
            $hodteammembers->data = $request->Team_Members ;

            $hodteammembers->save();
                // {{ Report_Approval }}
            $hodreportapproval = MarketComplaintGrids::where(['mc_id' => $griddata, 'identifer' => 'Report_Approval'])->firstOrNew();
            $hodreportapproval->mc_id = $griddata;
            $hodreportapproval->identifer = 'Report_Approval';
            $hodreportapproval->data = $request->Report_Approval ;
            $hodreportapproval->save();

            // {{ Product_MaterialDetails }}
            $caprduct = MarketComplaintGrids::where(['mc_id' => $griddata, 'identifer' => 'Product_MaterialDetails'])->firstOrNew();
            $caprduct->mc_id = $griddata;
            $caprduct->identifer = 'Product_MaterialDetails';
            $caprduct->data = $request->Product_MaterialDetails;
            // dd($caprduct);
            $caprduct->save();


                // {{  g}}
            $griddata = $marketComplaint->id;

            // Create MarketComplaintGrids record for Proposal to accomplish investigation
            $investigationData = [
                'Complaint sample Required' => ['csr1' => $request->csr1, 'csr2' => $request->csr2],
                'Additional info. From Complainant' => ['afc1' => $request->afc1, 'afc2' => $request->afc2],
                'Analysis of complaint Sample' => ['acs1' => $request->acs1, 'acs2' => $request->acs2],
                'QRM Approach' => ['qrm1' => $request->qrm1, 'qrm2' => $request->qrm2],
                'Others' => ['oth1' => $request->oth1, 'oth2' => $request->oth2]
            ];

            $marketrproducts = MarketComplaintGrids::where(['mc_id' => $griddata, 'identifer' => 'Proposal_to_accomplish_investigation'])->firstOrNew();
            $marketrproducts->mc_id = $griddata;
            $marketrproducts->identifer = 'Proposal_to_accomplish_investigation';
            $marketrproducts->data = json_encode($investigationData); // Encode data to JSON
            $marketrproducts->save();




        // return redirect()->route('qms-dashboard')->with('success', 'Market Complaint created successfully.');
        return redirect()->to('rcms/qms-dashboard')->with('success', 'Market Complaint created successfully.');

    }


    public function show($id)
{
                $data = MarketComplaint::find($id);
            $productsgi = MarketComplaintGrids::where('mc_id',$id)->where('identifer','ProductDetails')->first();
            $traceability_gi = MarketComplaintGrids::where('mc_id',$id)->where('identifer','Traceability')->first();
            $investing_team = MarketComplaintGrids::where('mc_id',$id)->where('identifer','Investing_team')->first();
            $brain_stroming_details = MarketComplaintGrids::where('mc_id',$id)->where('identifer','brain_stroming_details')->first();
            $team_members = MarketComplaintGrids::where('mc_id',$id)->where('identifer','Team_Members')->first();
            $report_approval = MarketComplaintGrids::where('mc_id',$id)->where('identifer','Report_Approval')->first();
            $product_materialDetails = MarketComplaintGrids::where('mc_id',$id)->where('identifer','Product_MaterialDetails')->first();
            // dd($product_materialDetails->data);
            // dd($product_materialDetails);
            // dd($data);
            // $productsgi = MarketComplaintGrids::where('mc_id',$id)->where('identifer','ProductDetails')->first();

            $proposal_to_accomplish_investigation = MarketComplaintGrids::where('mc_id', $id)->where('identifer', 'Proposal_to_accomplish_investigation')->first();
            $proposalData = $proposal_to_accomplish_investigation ? json_decode($proposal_to_accomplish_investigation->data, true) : [];


            // dd( $brain_stroming_session);
                return view('frontend.market_complaint.market_complaint_view',compact(
                    'data','productsgi','traceability_gi','investing_team','brain_stroming_details','team_members','report_approval','product_materialDetails','proposalData'));



    }





public function update(Request $request,$id)
{

    // $marketComplaint = MarketComplaint::find($id);
    // if (!$request->description_gi) {
    //     toastr()->info("Short Description is required");
    //     return redirect()->back()->withInput();
    // }

    $lastmarketComplaint = MarketComplaint::find($id);

    $marketComplaint = MarketComplaint::find($id);
    if (!$marketComplaint) {
        return redirect()->back()->with('error', 'Market Complaint not found.');
    }
    $marketComplaint->if_other_gi = $request->input('if_other_gi');
    $marketComplaint->initiator_group_code_gi = $request->initiator_group_code_gi;
    $marketComplaint->record =((RecordNumber::first()->value('counter')) + 1);
    $marketComplaint->initiated_through_gi = $request->initiated_through_gi;
    $marketComplaint->due_date_gi = $request->due_date_gi;

    $marketComplaint->if_other_gi = $request->if_other_gi;
    $marketComplaint->is_repeat_gi = $request->is_repeat_gi;
    $marketComplaint->repeat_nature_gi = $request->repeat_nature_gi;
    $marketComplaint->description_gi = $request->description_gi;
    // $marketComplaint->initial_attachment_gi = $request->initial_attachment_gi;
    $marketComplaint->complainant_gi = $request->complainant_gi;
    $marketComplaint->complaint_reported_on_gi = $request->complaint_reported_on_gi;
    $marketComplaint->details_of_nature_market_complaint_gi = $request->details_of_nature_market_complaint_gi;
    $marketComplaint->categorization_of_complaint_gi = $request->categorization_of_complaint_gi;
    $marketComplaint->review_of_complaint_sample_gi = $request->review_of_complaint_sample_gi;
    $marketComplaint->review_of_control_sample_gi = $request->review_of_control_sample_gi;
    $marketComplaint->review_of_batch_manufacturing_record_BMR_gi = $request->review_of_batch_manufacturing_record_BMR_gi;
    $marketComplaint->review_of_raw_materials_used_in_batch_manufacturing_gi = $request->review_of_raw_materials_used_in_batch_manufacturing_gi;
    $marketComplaint->review_of_Batch_Packing_record_bpr_gi = $request->review_of_Batch_Packing_record_bpr_gi;
    $marketComplaint->review_of_packing_materials_used_in_batch_packing_gi = $request->review_of_packing_materials_used_in_batch_packing_gi;
    $marketComplaint->review_of_analytical_data_gi = $request->review_of_analytical_data_gi;
    $marketComplaint->review_of_training_record_of_concern_persons_gi = $request->review_of_training_record_of_concern_persons_gi;
    $marketComplaint->rev_eq_inst_qual_calib_record_gi = $request->rev_eq_inst_qual_calib_record_gi;
    $marketComplaint->review_of_equipment_break_down_and_maintainance_record_gi = $request->review_of_equipment_break_down_and_maintainance_record_gi;
    $marketComplaint->review_of_past_history_of_product_gi = $request->review_of_past_history_of_product_gi;
    $marketComplaint->conclusion_hodsr = $request->conclusion_hodsr;
    $marketComplaint->root_cause_analysis_hodsr = $request->root_cause_analysis_hodsr;
    $marketComplaint->probable_root_causes_complaint_hodsr = $request->probable_root_causes_complaint_hodsr;
    $marketComplaint->impact_assessment_hodsr = $request->impact_assessment_hodsr;
    $marketComplaint->corrective_action_hodsr = $request->corrective_action_hodsr;
    $marketComplaint->preventive_action_hodsr = $request->preventive_action_hodsr;
    $marketComplaint->summary_and_conclusion_hodsr = $request->summary_and_conclusion_hodsr;
    // $marketComplaint->initial_attachment_hodsr = $request->initial_attachment_hodsr;
    $marketComplaint->comments_if_any_hodsr = $request->comments_if_any_hodsr;

    $marketComplaint->manufacturer_name_address_ca = $request->manufacturer_name_address_ca;
    $marketComplaint->complaint_sample_required_ca = $request->complaint_sample_required_ca;
    $marketComplaint->complaint_sample_status_ca = $request->complaint_sample_status_ca;
    $marketComplaint->brief_description_of_complaint_ca = $request->brief_description_of_complaint_ca;
    $marketComplaint->batch_record_review_observation_ca = $request->batch_record_review_observation_ca;
    $marketComplaint->analytical_data_review_observation_ca = $request->analytical_data_review_observation_ca;
    $marketComplaint->retention_sample_review_observation_ca = $request->retention_sample_review_observation_ca;
    $marketComplaint->stability_study_data_review_ca = $request->stability_study_data_review_ca;
    $marketComplaint->qms_events_ifany_review_observation_ca = $request->qms_events_ifany_review_observation_ca;
    $marketComplaint->repeated_complaints_queries_for_product_ca = $request->repeated_complaints_queries_for_product_ca;
    $marketComplaint->interpretation_on_complaint_sample_ifrecieved_ca = $request->interpretation_on_complaint_sample_ifrecieved_ca;
    $marketComplaint->comments_ifany_ca = $request->comments_ifany_ca;
    // $marketComplaint->initial_attachment_ca = $request->initial_attachment_ca;

    // Closure section
    $marketComplaint->closure_comment_c = $request->closure_comment_c;
    // $marketComplaint->initial_attachment_c = $request->initial_attachment_c;




    $marketComplaint->form_type="Market Complaint";
    //    dd($marketComplaint);


        // {{----.File attachemenet   }}


        // $files = [];
        // if ($request->hasFile('initial_attachment_gi')) {
        //     foreach ($request->file('initial_attachment_gi') as $file) {
        //         // Generate a unique name for the file
        //         $name = $request->name . 'initial_attachment_gi' . uniqid() . '.' . $file->getClientOriginalExtension();
                
        //         // Move the file to the upload directory
        //         $file->move(public_path('upload/'), $name);
                
        //         // Add the file name to the array
        //         $files[] = $name;
        //     }
        // }
        // // Encode the file names array to JSON and assign it to the model
        // $marketComplaint->initial_attachment_gi = json_encode($files);
        if ($request->hasFile('initial_attachment_gi')) {
            $files = [];
            foreach ($request->file('initial_attachment_gi') as $file) {
                $name = $request->name . '_initial_attachment_gi_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('upload/'), $name);
                $files[] = $name;
            }
            $marketComplaint->initial_attachment_gi = json_encode($files);
        }
    
        $marketComplaint->fill($request->except('initial_attachment_gi'));
    

        if ($request->hasFile('initial_attachment_hodsr')) {
            $files = [];
            foreach ($request->file('initial_attachment_hodsr') as $file) {
                $name = $request->name . '_initial_attachment_hodsr_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('upload/'), $name);
                $files[] = $name;
            }
            $marketComplaint->initial_attachment_hodsr = json_encode($files);
        }
        $marketComplaint->fill($request->except('initial_attachment_hodsr'));


        if ($request->hasFile('initial_attachment_ca')) {
            $files = [];
            foreach ($request->file('initial_attachment_ca') as $file) {
                $name = $request->name . '_initial_attachment_ca_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('upload/'), $name);
                $files[] = $name;
            }
            $marketComplaint->initial_attachment_ca = json_encode($files);
        }
        $marketComplaint->fill($request->except('initial_attachment_ca'));
    

        if ($request->hasFile('initial_attachment_c')) {
            $files = [];
            foreach ($request->file('initial_attachment_c') as $file) {
                $name = $request->name . '_initial_attachment_c_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('upload/'), $name);
                $files[] = $name;
            }
            $marketComplaint->initial_attachment_c = json_encode($files);
        }
        $marketComplaint->fill($request->except('initial_attachment_c'));
    

        // $files = [];
        // if ($request->hasFile('initial_attachment_hodsr')) {
        //     foreach ($request->file('initial_attachment_hodsr') as $file) {
        //         $name = $request->name . 'initial_attachment_hodsr' . uniqid() . '.' . $file->getClientOriginalExtension();
                
        //         $file->move(public_path('upload/'), $name);
                
        //         // Add the file name to the array
        //         $files[] = $name;
        //     }
        // }
        // // Encode the file names array to JSON and assign it to the model
        // $marketComplaint->initial_attachment_hodsr = json_encode($files);
       
        // $files = [];
        // if ($request->hasFile('initial_attachment_ca')) {
        //     foreach ($request->file('initial_attachment_ca') as $file) {
        //         // Generate a unique name for the file
        //         $name = $request->name . 'initial_attachment_ca' . uniqid() . '.' . $file->getClientOriginalExtension();
                
        //         // Move the file to the upload directory
        //         $file->move(public_path('upload/'), $name);
                
        //         // Add the file name to the array
        //         $files[] = $name;
        //     }
        // }
        // // Encode the file names array to JSON and assign it to the model
        // $marketComplaint->initial_attachment_ca = json_encode($files);

        
        // $files = [];
        // if ($request->hasFile('initial_attachment_c')) {
        //     foreach ($request->file('initial_attachment_c') as $file) {
        //         // Generate a unique name for the file
        //         $name = $request->name . 'initial_attachment_c' . uniqid() . '.' . $file->getClientOriginalExtension();
                
        //         // Move the file to the upload directory
        //         $file->move(public_path('upload/'), $name);
                
        //         // Add the file name to the array
        //         $files[] = $name;
        //     }
        // }
        // // Encode the file names array to JSON and assign it to the model
        // $marketComplaint->initial_attachment_c = json_encode($files);


       
       
        // dd($marketComplaint);



        // -------------------------audit show conditon--codestart----------------------------------



         if ( $lastmarketComplaint->review_of_past_history_of_product_gi != $marketComplaint->review_of_past_history_of_product_gi ) {
                $history = new MarketComplaintAuditTrial();
                $history->market_id = $marketComplaint->id;
                $history->activity_type = 'Review of Past history of product';
                $history->previous = $lastmarketComplaint->review_of_past_history_of_product_gi;
                $history->current = $marketComplaint->review_of_past_history_of_product_gi;
                $history->comment = $request->review_of_past_history_of_product_gi_comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $marketComplaint->status;
                $history->change_to = "Not Applicable";
                $history->change_from = $marketComplaint->status;
                $history->action_name = "Update";

                $history->save();
            }

            if ( $lastmarketComplaint->review_of_equipment_break_down_and_maintainance_record_gi != $marketComplaint->review_of_equipment_break_down_and_maintainance_record_gi ) {
                $history = new MarketComplaintAuditTrial();
                $history->market_id = $marketComplaint->id;
                $history->activity_type = 'Review of Equipment Break-down and Maintainance Record';
                $history->previous = $lastmarketComplaint->review_of_equipment_break_down_and_maintainance_record_gi;
                $history->current = $marketComplaint->review_of_equipment_break_down_and_maintainance_record_gi;
                $history->comment = $request->review_of_equipment_break_down_and_maintainance_record_gi_comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $marketComplaint->status;
                  $history->change_to = "Not Applicable";
                $history->change_from = $marketComplaint->status;
                $history->action_name = "Update";
                $history->save();
            }
            if ( $lastmarketComplaint->rev_eq_inst_qual_calib_record_gi != $marketComplaint->rev_eq_inst_qual_calib_record_gi ) {
                $history = new MarketComplaintAuditTrial();
                $history->market_id = $marketComplaint->id;
                $history->activity_type = 'Review of Equipment Instrument qualification Calibration record';
                $history->previous = $lastmarketComplaint->rev_eq_inst_qual_calib_record_gi;
                $history->current = $marketComplaint->rev_eq_inst_qual_calib_record_gi;
                $history->comment = $request->rev_eq_inst_qual_calib_record_gi_comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $marketComplaint->status;
                  $history->change_to = "Not Applicable";
                $history->change_from = $marketComplaint->status;
                $history->action_name = "Update";
                $history->save();
            }

            if ( $lastmarketComplaint->review_of_training_record_of_concern_persons_gi != $marketComplaint->review_of_training_record_of_concern_persons_gi) {
                $history = new MarketComplaintAuditTrial();
                $history->market_id = $marketComplaint->id;
                $history->activity_type = 'Review of training record of Concern Persons';
                $history->previous = $lastmarketComplaint->review_of_training_record_of_concern_persons_gi;
                $history->current = $marketComplaint->review_of_training_record_of_concern_persons_gi;
                $history->comment = $request->review_of_training_record_of_concern_persons_gi_comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $marketComplaint->status;
                  $history->change_to = "Not Applicable";
                $history->change_from = $marketComplaint->status;
                $history->action_name = "Update";
                $history->save();
            }
            if ( $lastmarketComplaint->review_of_analytical_data_gi != $marketComplaint->review_of_analytical_data_gi ) {
                $history = new MarketComplaintAuditTrial();
                $history->market_id = $marketComplaint->id;
                $history->activity_type = 'Review of Analytical Data';
                $history->previous = $lastmarketComplaint->review_of_analytical_data_gi;
                $history->current = $marketComplaint->review_of_analytical_data_gi;
                $history->comment = $request->review_of_analytical_data_gi_comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $marketComplaint->status;
                  $history->change_to = "Not Applicable";
                $history->change_from = $marketComplaint->status;
                $history->action_name = "Update";
                $history->save();
            }
            if ( $lastmarketComplaint->review_of_Batch_Packing_record_bpr_gi != $marketComplaint->review_of_Batch_Packing_record_bpr_gi ) {
                $history = new MarketComplaintAuditTrial();
                $history->market_id = $marketComplaint->id;
                $history->activity_type = 'Review of Batch Packing record BPR';
                $history->previous = $lastmarketComplaint->review_of_Batch_Packing_record_bpr_gi;
                $history->current = $marketComplaint->review_of_Batch_Packing_record_bpr_gi;
                $history->comment = $request->review_of_Batch_Packing_record_bpr_gi_comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $marketComplaint->status;
                  $history->change_to = "Not Applicable";
                $history->change_from = $marketComplaint->status;
                $history->action_name = "Update";
                $history->save();
            }

            if ( $marketComplaint->review_of_packing_materials_used_in_batch_packing_gi != $marketComplaint->review_of_packing_materials_used_in_batch_packing_gi ) {
                $history = new MarketComplaintAuditTrial();
                $history->market_id = $marketComplaint->id;
                $history->activity_type = 'Review of packing materials used in batch packing';
                $history->previous = $lastmarketComplaint->review_of_packing_materials_used_in_batch_packing_gi;
                $history->current = $marketComplaint->review_of_packing_materials_used_in_batch_packing_gi;
                $history->comment = $request->review_of_packing_materials_used_in_batch_packing_gi_comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $marketComplaint->status;
                  $history->change_to = "Not Applicable";
                $history->change_from = $marketComplaint->status;
                $history->action_name = "Update";
                $history->save();
            }
            if ( $lastmarketComplaint->review_of_raw_materials_used_in_batch_manufacturing_gi != $marketComplaint->review_of_raw_materials_used_in_batch_manufacturing_gi ) {
                $history = new MarketComplaintAuditTrial();
                $history->market_id = $marketComplaint->id;
                $history->activity_type = 'Review of Raw materials used in batch man';
                $history->previous = $lastmarketComplaint->review_of_raw_materials_used_in_batch_manufacturing_gi;
                $history->current = $marketComplaint->review_of_raw_materials_used_in_batch_manufacturing_gi;
                $history->comment = $request->review_of_raw_materials_used_in_batch_manufacturing_gi_comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $marketComplaint->status;
                $history->save();
            }
            if ( $lastmarketComplaint->review_of_control_sample_gi != $marketComplaint->review_of_control_sample_gi ) {
                $history = new MarketComplaintAuditTrial();
                $history->market_id = $marketComplaint->id;
                $history->activity_type = 'Review of Control Sample';
                $history->previous = $lastmarketComplaint->review_of_control_sample_gi;
                $history->current = $marketComplaint->review_of_control_sample_gi;
                $history->comment = $request->review_of_control_sample_gi_comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $marketComplaint->status;
                $history->change_to = "Not Applicable";
                $history->change_from = $marketComplaint->status;
                $history->action_name = "Update";
                $history->save();
            }


            if ( $lastmarketComplaint->review_of_batch_manufacturing_record_BMR_gi != $marketComplaint->review_of_batch_manufacturing_record_BMR_gi ) {
                $history = new MarketComplaintAuditTrial();
                $history->market_id = $marketComplaint->id;
                $history->activity_type = 'Review of Batch manufacturing record';
                $history->previous = $lastmarketComplaint->review_of_batch_manufacturing_record_BMR_gi;
                $history->current = $marketComplaint->review_of_batch_manufacturing_record_BMR_gi;
                $history->comment = $request->review_of_batch_manufacturing_record_BMR_gi_comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $marketComplaint->status;
                $history->change_to = "Not Applicable";
                $history->change_from = $marketComplaint->status;
                $history->action_name = "Update";
                $history->save();
            }

            if ( $lastmarketComplaint->review_of_complaint_sample_gi != $marketComplaint->review_of_complaint_sample_gi) {
                $history = new MarketComplaintAuditTrial();
                $history->market_id = $marketComplaint->id;
                $history->activity_type = 'Review of Complaint Sample';
                $history->previous = $lastmarketComplaint->review_of_complaint_sample_gi;
                $history->current = $marketComplaint->review_of_complaint_sample_gi;
                $history->comment = $request->review_of_complaint_sample_gi_comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $marketComplaint->status;
                $history->change_to = "Not Applicable";
                $history->change_from = $marketComplaint->status;
                $history->action_name = "Update";
                $history->save();
            }


        if ( $lastmarketComplaint->description_gi != $marketComplaint->description_gi) {
            $history = new MarketComplaintAuditTrial();
            $history->market_id = $marketComplaint->id;
            $history->activity_type = 'Short Description';
            $history->previous = $lastmarketComplaint->description_gi;
            $history->current = $marketComplaint->description_gi;
            $history->comment = $request->description_gi_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $marketComplaint->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $marketComplaint->status;
            $history->action_name = "Update";
            $history->save();
        }



        if ( $lastmarketComplaint->initiator_group != $marketComplaint->initiator_group ) {
            $history = new MarketComplaintAuditTrial();
            $history->market_id = $marketComplaint->id;
            $history->activity_type = 'Initiator Group';
            $history->previous = $lastmarketComplaint->initiator_group;
            $history->current = $marketComplaint->initiator_group;
            $history->comment = $request->initiator_group_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $marketComplaint->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $marketComplaint->status;
            $history->action_name = "Update";
            $history->save();
        }

        if ( $lastmarketComplaint->initiated_through_gi != $marketComplaint->initiated_through_gi ) {
            $history = new MarketComplaintAuditTrial();
            $history->market_id = $marketComplaint->id;
            $history->activity_type = 'Initiated Through';
            $history->previous = $lastmarketComplaint->initiated_through_gi;
            $history->current = $marketComplaint->initiated_through_gi;
            $history->comment = $request->initiated_through_gi_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $marketComplaint->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $marketComplaint->status;
            $history->action_name = "Update";
            $history->save();
        }

        if ( $marketComplaint->if_other_gi != $marketComplaint->if_other_gi ) {
            $history = new MarketComplaintAuditTrial();
            $history->market_id = $marketComplaint->id;
            $history->activity_type = 'If Other ';
            $history->previous = $lastmarketComplaint->if_other_gi;
            $history->current = $marketComplaint->if_other_gi;
            $history->comment = $request->if_other_gi_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $marketComplaint->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $marketComplaint->status;
            $history->action_name = "Update";
            $history->save();
        }

        if ( $lastmarketComplaint->is_repeat_gi != $marketComplaint->is_repeat_gi ) {
            $history = new MarketComplaintAuditTrial();
            $history->market_id = $marketComplaint->id;
            $history->activity_type = 'Is Repeat';
            $history->previous = $lastmarketComplaint->is_repeat_gi;
            $history->current = $marketComplaint->is_repeat_gi;
            $history->comment = $request->is_repeat_gi_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $marketComplaint->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $marketComplaint->status;
            $history->action_name = "Update";
            $history->save();
        }


        if ( $lastmarketComplaint->repeat_nature_gi != $marketComplaint->repeat_nature_gi ) {
            $history = new MarketComplaintAuditTrial();
            $history->market_id = $marketComplaint->id;
            $history->activity_type = 'Repeat Nature';
            $history->previous = $lastmarketComplaint->repeat_nature_gi;
            $history->current = $marketComplaint->repeat_nature_gi;
            $history->comment = $request->repeat_nature_gi_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $marketComplaint->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $marketComplaint->status;
            $history->action_name = "Update";
            $history->save();
        }


        if ( $lastmarketComplaint->initial_attachment_gi != $marketComplaint->initial_attachment_gi ) {
            $history = new MarketComplaintAuditTrial();
            $history->market_id = $marketComplaint->id;
            $history->activity_type = 'Information Attachment';
            $history->previous = $lastmarketComplaint->initial_attachment_gi;
            $history->current = $marketComplaint->initial_attachment_gi;
            $history->comment = $request->initial_attachment_gi_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $marketComplaint->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $marketComplaint->status;
            $history->action_name = "Update";
            $history->save();
        }

        if ( $lastmarketComplaint->complainant_gi != $marketComplaint->complainant_gi ) {
            $history = new MarketComplaintAuditTrial();
            $history->market_id = $marketComplaint->id;
            $history->activity_type = 'Complainant';
            $history->previous = $lastmarketComplaint->complainant_gi;
            $history->current = $marketComplaint->complainant_gi;
            $history->comment = $request->complainant_gi_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $marketComplaint->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $marketComplaint->status;
            $history->action_name = "Update";
            $history->save();
        }

        if ( $marketComplaint->complaint_reported_on_gi != $marketComplaint->complaint_reported_on_gi ) {
            $history = new MarketComplaintAuditTrial();
            $history->market_id = $marketComplaint->id;
            $history->activity_type = 'Complaint Reported On';
            $history->previous = $lastmarketComplaint->complaint_reported_on_gi;
            $history->current = $marketComplaint->complaint_reported_on_gi;
            $history->comment = $request->complaint_reported_on_gi_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $marketComplaint->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $marketComplaint->status;
            $history->action_name = "Update";
            $history->save();
        }

        if ( $lastmarketComplaint->details_of_nature_market_complaint_gi != $marketComplaint->details_of_nature_market_complaint_gi) {
            $history = new MarketComplaintAuditTrial();
            $history->market_id = $marketComplaint->id;
            $history->activity_type = 'Details Of Nature Market Complaint';
            $history->previous = $lastmarketComplaint->details_of_nature_market_complaint_gi;
            $history->current = $marketComplaint->details_of_nature_market_complaint_gi;
            $history->comment = $request->details_of_nature_market_complaint_gi_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $marketComplaint->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $marketComplaint->status;
            $history->action_name = "Update";
            $history->save();
        }

        if ( $lastmarketComplaint->categorization_of_complaint_gi != $marketComplaint->categorization_of_complaint_gi ) {
            $history = new MarketComplaintAuditTrial();
            $history->market_id = $marketComplaint->id;
            $history->activity_type = 'Categorization of complaint';
            $history->previous = $lastmarketComplaint->categorization_of_complaint_gi;
            $history->current = $marketComplaint->categorization_of_complaint_gi;
            $history->comment = $request->categorization_of_complaint_gi_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $marketComplaint->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $marketComplaint->status;
            $history->action_name = "Update";
            $history->save();
        }
        // -------------------------------------------------------hod audit show filds ----------------------------------------------

        if ( $lastmarketComplaint->conclusion_hodsr != $marketComplaint->conclusion_hodsr ) {
            $history = new MarketComplaintAuditTrial();
            $history->market_id = $marketComplaint->id;
            $history->activity_type = 'Conclusion';
            $history->previous = $lastmarketComplaint->conclusion_hodsr;
            $history->current = $marketComplaint->conclusion_hodsr;
            $history->comment = $request->conclusion_hodsr_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $marketComplaint->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $marketComplaint->status;
            $history->action_name = "Update";
            $history->save();
        }
        if ( $marketComplaint->root_cause_analysis_hodsr != $marketComplaint->root_cause_analysis_hodsr ) {
            $history = new MarketComplaintAuditTrial();
            $history->market_id = $marketComplaint->id;
            $history->activity_type = 'Root Cause Analysis';
            $history->previous = $lastmarketComplaint->root_cause_analysis_hodsr;
            $history->current = $marketComplaint->root_cause_analysis_hodsr;
            $history->comment = $request->root_cause_analysis_hodsr_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $marketComplaint->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $marketComplaint->status;
            $history->action_name = "Update";
            $history->save();
        }
        if ( $lastmarketComplaint->probable_root_causes_complaint_hodsr != $marketComplaint->probable_root_causes_complaint_hodsr ) {
            $history = new MarketComplaintAuditTrial();
            $history->market_id = $marketComplaint->id;
            $history->activity_type = 'The most probable root causes identified of the complaint are as below';
            $history->previous = $lastmarketComplaint->probable_root_causes_complaint_hodsr;
            $history->current = $marketComplaint->probable_root_causes_complaint_hodsr;
            $history->comment = $request->probable_root_causes_complaint_hodsr_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $marketComplaint->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $marketComplaint->status;
            $history->action_name = "Update";
            $history->save();
        }
        if ( $lastmarketComplaint->impact_assessment_hodsr != $marketComplaint->impact_assessment_hodsr ) {
            $history = new MarketComplaintAuditTrial();
            $history->market_id = $marketComplaint->id;
            $history->activity_type = 'Impact Assessment';
            $history->previous = $lastmarketComplaint->impact_assessment_hodsr;
            $history->current = $marketComplaint->impact_assessment_hodsr;
            $history->comment = $request->impact_assessment_hodsr_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $marketComplaint->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $marketComplaint->status;
            $history->action_name = "Update";
            $history->save();
        }
        if ( $lastmarketComplaint->corrective_action_hodsr != $marketComplaint->corrective_action_hodsr ) {
            $history = new MarketComplaintAuditTrial();
            $history->market_id = $marketComplaint->id;
            $history->activity_type = 'Corrective Action';
            $history->previous = $lastmarketComplaint->corrective_action_hodsr;
            $history->current = $marketComplaint->corrective_action_hodsr;
            $history->comment = $request->corrective_action_hodsr_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $marketComplaint->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $marketComplaint->status;
            $history->action_name = "Update";
            $history->save();
        }

        if ( $lastmarketComplaint->preventive_action_hodsr != $marketComplaint->preventive_action_hodsr ) {
            $history = new MarketComplaintAuditTrial();
            $history->market_id = $marketComplaint->id;
            $history->activity_type = 'Preventive Action';
            $history->previous = $lastmarketComplaint->preventive_action_hodsr;
            $history->current = $marketComplaint->preventive_action_hodsr;
            $history->comment = $request->preventive_action_hodsr_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $marketComplaint->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $marketComplaint->status;
            $history->action_name = "Update";
            $history->save();
        }
        if ( $marketComplaint->summary_and_conclusion_hodsr != $marketComplaint->summary_and_conclusion_hodsr ) {
            $history = new MarketComplaintAuditTrial();
            $history->market_id = $marketComplaint->id;
            $history->activity_type = 'Summary and Conclusion';
            $history->previous = $lastmarketComplaint->summary_and_conclusion_hodsr;
            $history->current = $marketComplaint->summary_and_conclusion_hodsr;
            $history->comment = $request->summary_and_conclusion_hodsr_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $marketComplaint->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $marketComplaint->status;
            $history->action_name = "Update";
            $history->save();
        }
        if ( $lastmarketComplaint->initial_attachment_hodsr != $marketComplaint->initial_attachment_hodsr ) {
            $history = new MarketComplaintAuditTrial();
            $history->market_id = $marketComplaint->id;
            $history->activity_type = 'HOD Attachment';
            $history->previous = $lastmarketComplaint->initial_attachment_hodsr;
            $history->current = $marketComplaint->initial_attachment_hodsr;
            $history->comment = $request->initial_attachment_hodsr_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $marketComplaint->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $marketComplaint->status;
            $history->action_name = "Update";
            $history->save();
        }

        if ( $lastmarketComplaint->comments_if_any_hodsr != $marketComplaint->comments_if_any_hodsr ) {
            $history = new MarketComplaintAuditTrial();
            $history->market_id = $marketComplaint->id;
            $history->activity_type = 'Comments if Any';
            $history->previous = $lastmarketComplaint->comments_if_any_hodsr;
            $history->current = $marketComplaint->comments_if_any_hodsr;
            $history->comment = $request->comments_if_any_hodsr_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $marketComplaint->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $marketComplaint->status;
            $history->action_name = "Update";
            $history->save();
        }
    // ------------------------------------------------c a audit show data---------------------

        if ( $lastmarketComplaint->manufacturer_name_address_ca != $marketComplaint->manufacturer_name_address_ca ) {
            $history = new MarketComplaintAuditTrial();
            $history->market_id = $marketComplaint->id;
            $history->activity_type = 'Manufacturer name & Address';
            $history->previous = $lastmarketComplaint->manufacturer_name_address_ca;
            $history->current = $marketComplaint->manufacturer_name_address_ca;
            $history->comment = $request->manufacturer_name_address_ca_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $marketComplaint->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $marketComplaint->status;
            $history->action_name = "Update";
            
            $history->save();
        }

        if ( $lastmarketComplaint->complaint_sample_required_ca != $marketComplaint->complaint_sample_required_ca ) {
            $history = new MarketComplaintAuditTrial();
            $history->market_id = $marketComplaint->id;
            $history->activity_type = 'Complaint Sample Required';
            $history->previous = $lastmarketComplaint->complaint_sample_required_ca;
            $history->current = $marketComplaint->complaint_sample_required_ca;
            $history->comment = $request->complaint_sample_required_ca_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $marketComplaint->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $marketComplaint->status;
            $history->action_name = "Update";
            
            $history->save();
        }

        if ( $lastmarketComplaint->complaint_sample_status_ca != $marketComplaint->complaint_sample_status_ca ) {
            $history = new MarketComplaintAuditTrial();
            $history->market_id = $marketComplaint->id;
            $history->activity_type = 'Complaint Sample Status';
            $history->previous = $lastmarketComplaint->complaint_sample_status_ca;
            $history->current = $marketComplaint->complaint_sample_status_ca;
            $history->comment = $request->complaint_sample_status_ca_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $marketComplaint->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $marketComplaint->status;
            $history->action_name = "Update";
            $history->save();
        }


        if ( $lastmarketComplaint->brief_description_of_complaint_ca != $marketComplaint->brief_description_of_complaint_ca ) {
            $history = new MarketComplaintAuditTrial();
            $history->market_id = $marketComplaint->id;
            $history->activity_type = 'Brief Description of complaint';
            $history->previous = $lastmarketComplaint->brief_description_of_complaint_ca;
            $history->current = $marketComplaint->brief_description_of_complaint_ca;
            $history->comment = $request->brief_description_of_complaint_ca_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $marketComplaint->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $marketComplaint->status;
            $history->action_name = "Update";
            $history->save();
        }

        if ( $lastmarketComplaint->batch_record_review_observation_ca != $marketComplaint->batch_record_review_observation_ca ) {
            $history = new MarketComplaintAuditTrial();
            $history->market_id = $marketComplaint->id;
            $history->activity_type = 'Batch Record review observation';
            $history->previous = $lastmarketComplaint->batch_record_review_observation_ca;
            $history->current = $marketComplaint->batch_record_review_observation_ca;
            $history->comment = $request->batch_record_review_observation_ca_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $marketComplaint->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $marketComplaint->status;
            $history->action_name = "Update";
            $history->save();
        }
        if ( $lastmarketComplaint->analytical_data_review_observation_ca != $marketComplaint->analytical_data_review_observation_ca ) {
            $history = new MarketComplaintAuditTrial();
            $history->market_id = $marketComplaint->id;
            $history->activity_type = 'Analytical Data review observation';
            $history->previous = $lastmarketComplaint->analytical_data_review_observation_ca;
            $history->current = $marketComplaint->analytical_data_review_observation_ca;
            $history->comment = $request->analytical_data_review_observation_ca_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $marketComplaint->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $marketComplaint->status;
            $history->action_name = "Update";
            $history->save();
        }
        if ( $lastmarketComplaint->retention_sample_review_observation_ca != $marketComplaint->retention_sample_review_observation_ca ) {
            $history = new MarketComplaintAuditTrial();
            $history->market_id = $marketComplaint->id;
            $history->activity_type = 'Retention sample review observation';
            $history->previous = $lastmarketComplaint->retention_sample_review_observation_ca;
            $history->current = $marketComplaint->retention_sample_review_observation_ca;
            $history->comment = $request->retention_sample_review_observation_ca_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $marketComplaint->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $marketComplaint->status;
            $history->action_name = "Update";
            $history->save();
        }

        if ( $lastmarketComplaint->qms_events_ifany_review_observation_ca != $marketComplaint->qms_events_ifany_review_observation_ca ) {
            $history = new MarketComplaintAuditTrial();
            $history->market_id = $marketComplaint->id;
            $history->activity_type = 'QMS Events(if any) review Observation';
            $history->previous = $lastmarketComplaint->qms_events_ifany_review_observation_ca;
            $history->current = $marketComplaint->qms_events_ifany_review_observation_ca;
            $history->comment = $request->qms_events_ifany_review_observation_ca_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $marketComplaint->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $marketComplaint->status;
            $history->action_name = "Update";
            $history->save();
        }
        if ( $lastmarketComplaint->repeated_complaints_queries_for_product_ca != $marketComplaint->repeated_complaints_queries_for_product_ca ) {
            $history = new MarketComplaintAuditTrial();
            $history->market_id = $marketComplaint->id;
            $history->activity_type = 'Repeated complaints/queries for product';
            $history->previous = $lastmarketComplaint->repeated_complaints_queries_for_product_ca;
            $history->current = $marketComplaint->repeated_complaints_queries_for_product_ca;
            $history->comment = $request->repeated_complaints_queries_for_product_ca_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $marketComplaint->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $marketComplaint->status;
            $history->action_name = "Update";
            $history->save();
        }

        if ( $lastmarketComplaint->interpretation_on_complaint_sample_ifrecieved_ca != $marketComplaint->interpretation_on_complaint_sample_ifrecieved_ca ) {
            $history = new MarketComplaintAuditTrial();
            $history->market_id = $marketComplaint->id;
            $history->activity_type = 'Interpretation on compalint sample(if recieved)';
            $history->previous = $lastmarketComplaint->interpretation_on_complaint_sample_ifrecieved_ca;
            $history->current = $marketComplaint->interpretation_on_complaint_sample_ifrecieved_ca;
            $history->comment = $request->interpretation_on_complaint_sample_ifrecieved_ca_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $marketComplaint->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $marketComplaint->status;
            $history->action_name = "Update";
            $history->save();
        }
        if ( $lastmarketComplaint->comments_ifany_ca != $marketComplaint->comments_ifany_ca ) {
            $history = new MarketComplaintAuditTrial();
            $history->market_id = $marketComplaint->id;
            $history->activity_type = 'Comments(if Any)';
            $history->previous = $lastmarketComplaint->comments_ifany_ca;
            $history->current = $marketComplaint->comments_ifany_ca;
            $history->comment = $request->comments_ifany_ca_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $marketComplaint->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $marketComplaint->status;
            $history->action_name = "Update";
            $history->save();
        }

        if ( $lastmarketComplaint->initial_attachment_ca != $marketComplaint->initial_attachment_ca ) {
            $history = new MarketComplaintAuditTrial();
            $history->market_id = $marketComplaint->id;
            $history->activity_type = 'Acknowledgement Attachment';
            $history->previous = $lastmarketComplaint->initial_attachment_ca;
            $history->current = $marketComplaint->initial_attachment_ca;
            $history->comment = $request->initial_attachment_ca_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $marketComplaint->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $marketComplaint->status;
            $history->action_name = "Update";
            $history->save();
        }



        // -------------------------end audit show conditon end code ----------------------------------


     $marketComplaint->update();
        // dd($marketComplaint);
        // {{-- --produts grid gi  --}}




            // {{ grid update }}


        // For "Product Details"
        $griddata = $marketComplaint->id;

        // $marketrproducts = MarketComplaintGrids::where(['mc_id' => $griddata, 'identifer' => 'Product Details'])->firstOrNew();
        // $marketrproducts->mc_id = $griddata;
        // $marketrproducts->identifer = 'ProductDetails';
        // $marketrproducts->data = $request->serial_number_gi;
        // $marketrproducts->update();

        $product = MarketComplaintGrids::where(['mc_id' => $griddata, 'identifer' => 'ProductDetails'])->firstOrNew();
        $product->mc_id = $griddata;
        $product->identifer = 'ProductDetails';
        $product->data = $request->serial_number_gi;
        // dd( $product->data);

        $product->update();


        //Traceability
        // $griddata = $marketComplaint->id;

        $marketrproducts = MarketComplaintGrids::where(['mc_id' => $griddata, 'identifer' => 'Traceability'])->firstOrNew();
        $marketrproducts->mc_id = $griddata;
        $marketrproducts->identifer = 'Traceability';
        $marketrproducts->data = $request->trace_ability;
        // dd($marketrproducts);
        $marketrproducts->update();

            // {{-- Investing_team --}}
        $marketrproducts = MarketComplaintGrids::where(['mc_id' => $griddata, 'identifer' => 'Investing_team'])->firstOrNew();
        $marketrproducts->mc_id = $griddata;
        $marketrproducts->identifer = 'Investing_team';
        $marketrproducts->data = $request->Investing_team;
        $marketrproducts->update();
            // {{-- Brain stroming Session --}}

            $brain = MarketComplaintGrids::where(['mc_id' => $griddata, 'identifer' => 'brain_stroming_details'])->firstOrNew();
            $brain->mc_id = $griddata;
            $brain->identifer = 'brain_stroming_details';
            $brain->data = $request->brain_stroming_details;
            $brain->update();
                // {{ Team Member
            // {{ Team Members }}
        $marketrproducts = MarketComplaintGrids::where(['mc_id' => $griddata, 'identifer' => 'Team_Members'])->firstOrNew();
        $marketrproducts->mc_id = $griddata;
        $marketrproducts->identifer = 'Team_Members';
        $marketrproducts->data = $request->Team_Members ;
        $marketrproducts->update();
            // {{ Report_Approval }}
        $marketrproducts = MarketComplaintGrids::where(['mc_id' => $griddata, 'identifer' => 'Report_Approval'])->firstOrNew();
        $marketrproducts->mc_id = $griddata;
        $marketrproducts->identifer = 'Report_Approval';
        $marketrproducts->data = $request->Report_Approval ;
        $marketrproducts->update();

        // {{ Product_MaterialDetails }}
        $marketrproducts = MarketComplaintGrids::where(['mc_id' => $griddata, 'identifer' => 'Product_MaterialDetails'])->firstOrNew();
        $marketrproducts->mc_id = $griddata;
        $marketrproducts->identifer = 'Product_MaterialDetails';
        $marketrproducts->data = $request->Product_MaterialDetails ;
        // dd($marketrproducts->data);
        $marketrproducts->update();


            // {{  g}}
        $griddata = $marketComplaint->id;

        // Create MarketComplaintGrids record for Proposal to accomplish investigation
        $investigationData = [
            'Complaint sample Required' => ['csr1' => $request->csr1, 'csr2' => $request->csr2],
            'Additional info. From Complainant' => ['afc1' => $request->afc1, 'afc2' => $request->afc2],
            'Analysis of complaint Sample' => ['acs1' => $request->acs1, 'acs2' => $request->acs2],
            'QRM Approach' => ['qrm1' => $request->qrm1, 'qrm2' => $request->qrm2],
            'Others' => ['oth1' => $request->oth1, 'oth2' => $request->oth2]
        ];

        $marketrproducts = MarketComplaintGrids::where(['mc_id' => $griddata, 'identifer' => 'Proposal_to_accomplish_investigation'])->firstOrNew();
        $marketrproducts->mc_id = $griddata;
        $marketrproducts->identifer = 'Proposal_to_accomplish_investigation';
        $marketrproducts->data = json_encode($investigationData); // Encode data to JSON
        $marketrproducts->update();





    toastr()->success('Record is updated Successfully');
    return redirect()->back();
    //  return redirect()->route('marketcomplaint.marketcomplaintupdate' ,['id'=> $marketComplaint->id])->with('success', 'Market Complaint updated successfully.');

}






public function marketComplaintStateChange(Request $request,$id)
    {
        if ($request->username == Auth::user()->email && Hash::check($request->password, Auth::user()->password)) {
            $marketstat = MarketComplaint::find($id);
            $lastDocument =  MarketComplaint::find($id);
            $data = MarketComplaint::find($id);


           if( $marketstat->stage == 1){
            $marketstat->stage = "2";
            $marketstat->submitted_by = Auth::user()->name;
            $marketstat->submitted_on = Carbon::now()->format('d-M-Y');
            $marketstat->submitted_comment = $request->comment;


            $marketstat->status = "Opened";
            $history = new MarketComplaintAuditTrial();
            $history->market_id = $id;
            $history->activity_type = 'Activity Log';
            $history->current = $marketstat->submitted_by;
            $history->comment = $request->comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = "Opened";
            $history->change_from = $lastDocument->status;
            $history->stage='Submited';
            $history->save();

            $marketstat->update();

            return redirect()->back();
           }
           if( $marketstat->stage == 2)
           {
            $marketstat->stage = "3";
            $marketstat->complete_review_by = Auth::user()->name;
            $marketstat->complete_review_on = Carbon::now()->format('d-M-Y');
            $marketstat->complete_review_comment = $request->comment;


            $marketstat->status ="Supervisor Review";
            $history = new MarketComplaintAuditTrial();
            $history->market_id = $id;
            $history->activity_type = 'Activity Log';
            $history->current = $marketstat->complete_review_by;
            $history->comment = $request->comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = "Supervisor Review";
            $history->change_from = $lastDocument->status;
            $history->stage='Complete Review';
            $history->save();



            // dd($marketstat->stage);
            $marketstat->update();
            return redirect()->back();
           }

           if( $marketstat->stage == 3)
           {
            $marketstat->stage = "4";
            $marketstat->investigation_completed_by = Auth::user()->name;
            $marketstat->investigation_completed_on = Carbon::now()->format('d-M-Y');
            $marketstat->investigation_completed_comment = $request->comment;



            $marketstat->status ="Investigation and Root Cause Analysis";
            $history = new MarketComplaintAuditTrial();
            $history->market_id = $id;
            $history->activity_type = 'Activity Log';
            $history->current = $marketstat->investigation_completed_by;
            $history->comment = $request->comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = "Opened";
            $history->change_from = $lastDocument->status;
            $history->stage='Investigation and Root Cause Analysis';
            $history->save();



            $marketstat->update();
            return redirect()->back();
           }

           if( $marketstat->stage == 4)
           {
            $marketstat->stage = "5";
            $marketstat->propose_plan_by = Auth::user()->name;
            $marketstat->propose_plan_on = Carbon::now()->format('d-M-Y');
            $marketstat->propose_plan_comment = $request->comment;

            $marketstat->status ="CAPA Plan";
            $history = new MarketComplaintAuditTrial();
            $history->market_id = $id;
            $history->activity_type = 'Activity Log';
            $history->current = $marketstat->propose_plan_by;
            $history->comment = $request->comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = "CAPA Plan";
            $history->change_from = $lastDocument->status;
            $history->stage='Propose Plan';
            $history->save();



            $marketstat->update();
            return redirect()->back();
           }

           if( $marketstat->stage == 5)
           {
            $marketstat->stage = "6";
            $marketstat->approve_plan_by = Auth::user()->name;
            $marketstat->approve_plan_on = Carbon::now()->format('d-M-Y');
            $marketstat->approve_plan_comment = $request->comment;


            $marketstat->status ="Pending Approval";
            $history = new MarketComplaintAuditTrial();
            $history->market_id = $id;
            $history->activity_type = 'Activity Log';
            $history->current = $marketstat->approve_plan_by;
            $history->comment = $request->comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = "Pending Approval";
            $history->change_from = $lastDocument->status;
            $history->stage='Approve Plan';
            $history->save();



            $marketstat->update();
            return redirect()->back();
           }

           if( $marketstat->stage == 6)
           {
            $marketstat->stage = "7";
            $marketstat->all_capa_closed_by = Auth::user()->name;
            $marketstat->all_capa_closed_on = Carbon::now()->format('d-M-Y');
            $marketstat->all_capa_closed_comment = $request->comment;


            $marketstat->status ="Pending Actions Completion";
            $history = new MarketComplaintAuditTrial();
            $history->market_id = $id;
            $history->activity_type = 'Activity Log';
            $history->current = $marketstat->all_capa_closed_by;
            $history->comment = $request->comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = "Pending Actions Completion";
            $history->change_from = $lastDocument->status;
            $history->stage='All CAPA Closed';
            $history->save();




            $marketstat->update();
            return redirect()->back();
           }

           if( $marketstat->stage == 7)
           {
            $marketstat->stage = "8";
            $marketstat->closed_done_by = Auth::user()->name;
            $marketstat->closed_done_on = Carbon::now()->format('d-M-Y');
            $marketstat->closed_done_comment = $request->comment;


            $marketstat->status ="Closed Done";
            $history = new MarketComplaintAuditTrial();
            $history->market_id = $id;
            $history->activity_type = 'Activity Log';
            $history->current = $marketstat->closed_done_by;
            $history->comment = $request->comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = "Pending Response Letter";
            $history->change_from = $lastDocument->status;
            $history->stage='Send Letter';
            $history->save();


            $marketstat->update();
            return redirect()->back();
           }



        }else {
            toastr()->error('E-signature Not match');
            return back();
        }
    }

    public function marketComplaintRejectState(Request $request, $id)
    {
        if ($request->username == Auth::user()->email && Hash::check($request->password, Auth::user()->password)) {
            $changeControl = MarketComplaint::find($id);

            if ($changeControl->stage == 2) {
                $changeControl->stage = "1";
                $changeControl->status = "Opened";
                $changeControl->update();

                return back();
            }
            if ($changeControl->stage == 3) {
                $changeControl->stage = "1";
                $changeControl->status = "Opened";
                $changeControl->update();

                return back();
            }

            if ($changeControl->stage == 5) {
                $changeControl->stage = "4";
                $changeControl->status = "CAPA Plan";
                $changeControl->update();

                return back();
            }
        }

        // Optionally, handle invalid credentials or other logic
        return back()->withErrors(['Invalid credentials or action not allowed.']);
    }


    public function MarketComplaintCancel(Request $request, $id)
    {
        if ($request->username == Auth::user()->email && Hash::check($request->password, Auth::user()->password)) {
            $changeControl = MarketComplaint::find($id);

            if ($changeControl->stage == 2) {
                $changeControl->stage = "0";
                $changeControl->status = "Closed - Cancelled";
                $changeControl->update();
                toastr()->success('Document Sent');
                return back();
            }


            // $changeControl->stage = "2";
            // // $changeControl->status = "Closed - Cancelled";
            // $changeControl->cancelled_by = Auth::user()->name;
            // $changeControl->cancelled_on = Carbon::now()->format('d-M-Y');
            // $changeControl->update();
            // toastr()->success('Document Sent');
            // return back();
        } else {
            toastr()->error('E-signature Not match');
            return back();
        }
    }




// =======================================================RCA and Action ======================================================

public function MarketComplaintRca_actionChild(Request $request,$id)
{
    // dd($request->revision);
    
    $cc = MarketComplaint::find($id);
    $cft = [];
    $parent_id = $id;
    $parent_type = "Capa";
    $old_record = Capa::select('id', 'division_id', 'record')->get();
    $record = ((RecordNumber::first()->value('counter')) + 1);
    $record = str_pad($record, 4, '0', STR_PAD_LEFT);
    $currentDate = Carbon::now();
    $formattedDate = $currentDate->addDays(30);
    $due_date = $formattedDate->format('d-M-Y');
    $parent_intiation_date = Capa::where('id', $id)->value('intiation_date');
    $parent_record =  ((RecordNumber::first()->value('counter')) + 1);
    $parent_record = str_pad($parent_record, 4, '0', STR_PAD_LEFT);
    $parent_initiator_id = $id;
   
    if ($request->revision == "rca-child") {
        $cc->originator = User::where('id', $cc->initiator_id)->value('name');
        return view('frontend.forms.root-cause-analysis', compact('record', 'due_date', 'parent_id','old_record', 'parent_type','parent_intiation_date','parent_record','parent_initiator_id','cft'));

    }
    if ($request->revision == "Action-Item") {
        // return "test";
        $cc->originator = User::where('id', $cc->initiator_id)->value('name');
        return view('frontend.action-item.action-item', compact('record', 'due_date', 'parent_id','old_record', 'parent_type','parent_intiation_date','parent_record','parent_initiator_id'));

    }
    

}




// ================================================================Capa and Action==================================================

    public function MarketComplaintCapa_ActionChild(Request $request,$id)
    {
        // dd($request->revision);
        
        $cc = MarketComplaint::find($id);
        $cft = [];
        $parent_id = $id;
        $parent_type = "Capa";
        $old_record = Capa::select('id', 'division_id', 'record')->get();
        $record = ((RecordNumber::first()->value('counter')) + 1);
        $record = str_pad($record, 4, '0', STR_PAD_LEFT);
        $currentDate = Carbon::now();
        $formattedDate = $currentDate->addDays(30);
        $due_date = $formattedDate->format('d-M-Y');
        $parent_intiation_date = Capa::where('id', $id)->value('intiation_date');
        $parent_record =  ((RecordNumber::first()->value('counter')) + 1);
        $parent_record = str_pad($parent_record, 4, '0', STR_PAD_LEFT);
        $parent_initiator_id = $id;
       
        if ($request->revision == "capa-child") {
            $cc->originator = User::where('id', $cc->initiator_id)->value('name');
            return view('frontend.forms.capa', compact('record', 'due_date', 'parent_id','old_record', 'parent_type','parent_intiation_date','parent_record','parent_initiator_id','cft'));

        }
        if ($request->revision == "Action-Item") {
            // return "test";
            $cc->originator = User::where('id', $cc->initiator_id)->value('name');
            return view('frontend.action-item.action-item', compact('record', 'due_date', 'parent_id','old_record', 'parent_type','parent_intiation_date','parent_record','parent_initiator_id'));

        }
        

    }
// {{-- ==================================Regulatory  Reporting  and Effectiveness  Check child=============================================== --}}

        public function MarketComplaintRegu_Effec_Child(Request $request,$id)
        {
            // dd($request->revision);
            
            $cc = MarketComplaint::find($id);
            $cft = [];
            $parent_id = $id;
            $parent_type = "Capa";
            $old_record = Capa::select('id', 'division_id', 'record')->get();
            $record = ((RecordNumber::first()->value('counter')) + 1);
            $record = str_pad($record, 4, '0', STR_PAD_LEFT);
            $currentDate = Carbon::now();
            $formattedDate = $currentDate->addDays(30);
            $due_date = $formattedDate->format('d-M-Y');
            $parent_intiation_date = Capa::where('id', $id)->value('intiation_date');
            $parent_record =  ((RecordNumber::first()->value('counter')) + 1);
            $parent_record = str_pad($parent_record, 4, '0', STR_PAD_LEFT);
            $parent_initiator_id = $id;
        
            if ($request->revision == "regulatory-child") {
                $cc->originator = User::where('id', $cc->initiator_id)->value('name');
                return "test";
                // return view('frontend.forms.capa', compact('record', 'due_date', 'parent_id','old_record', 'parent_type','parent_intiation_date','parent_record','parent_initiator_id','cft'));

            }
            if ($request->revision == "Effectiveness-child") {
                // return "test";
                $cc->originator = User::where('id', $cc->initiator_id)->value('name');
                return view('frontend.forms.effectiveness-check', compact('record', 'due_date', 'parent_id','old_record', 'parent_type','parent_intiation_date','parent_record','parent_initiator_id'));

            }
            

        }


    public function singleReport(Request $request, $id)
    {

        $data = MarketComplaint::find($id);
        $prductgigrid =MarketComplaintGrids::where(['mc_id' => $id,'identifer' => 'ProductDetails'])->first();
        // $martab_grid =MarketComplaintGrids::where(['mc_id' => $id,'identifer'=> 'Sutability'])->first();




        if (!empty($data)) {
            $data->originator = User::where('id', $data->initiator_id)->value('name');
            $pdf = App::make('dompdf.wrapper');
            $time = Carbon::now();
            $pdf = PDF::loadview('frontend.market_complaint.singleReport', compact('data','prductgigrid'))
                ->setOptions([
                    'defaultFont' => 'sans-serif',
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled' => true,
                    'isPhpEnabled' => true,
                ]);
            $pdf->setPaper('A4');
            $pdf->render();
            $canvas = $pdf->getDomPDF()->getCanvas();
            $height = $canvas->get_height();
            $width = $canvas->get_width();
            $canvas->page_script('$pdf->set_opacity(0.1,"Multiply");');
            $canvas->page_text($width / 4, $height / 2, $data->status, null, 25, [0, 0, 0], 2, 6, -20);
            return $pdf->stream('MarketComplainta' . $id . '.pdf');
        }


        return view('frontend.market_complaint.singleReport',compact('data','prductgigrid'));

    }

    public function MarketAuditTrial($id)
    {


        $audit = MarketComplaintAuditTrial::where('market_id', $id)->orderByDESC('id')->paginate();
        $today = Carbon::now()->format('d-m-y');
        $document = MarketComplaint::where('id', $id)->first();
        $document->initiator = User::where('id', $document->initiator_id)->value('name');

      
        return view('frontend.market_complaint.audit-trial',compact('audit', 'document', 'today'));

    }




    public function auditDetailsMarket($id)
    {
        $detail = MarketComplaintAuditTrial::find($id);

        $detail_data = MarketComplaintAuditTrial::where('Activity_type', $detail->activity_type)->where('market_id', $detail->market_id)->latest()->get();

        $doc = MarketComplaint::where('id', $detail->market_id)->first();

        $doc->origiator_name = User::find($doc->initiator_id);
        return view('frontend.market_complaint.audit-trial-inner', compact('detail', 'doc', 'detail_data'));

    }


    public static function auditReport($id)
    {
        $doc = MarketComplaint::find($id);
        if (!empty($doc)) {
            $doc->originator = User::where('id', $doc->initiator_id)->value('name');
            $data = MarketComplaintAuditTrial::where('market_id', $id)->get();
            $pdf = App::make('dompdf.wrapper');
            $time = Carbon::now();
            $pdf = PDF::loadview('frontend.market_complaint.auditReport', compact('data', 'doc'))
                ->setOptions([
                    'defaultFont' => 'sans-serif',
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled' => true,
                    'isPhpEnabled' => true,
                ]);
            $pdf->setPaper('A4');
            $pdf->render();
            $canvas = $pdf->getDomPDF()->getCanvas();
            $height = $canvas->get_height();
            $width = $canvas->get_width();
            $canvas->page_script('$pdf->set_opacity(0.1,"Multiply");');
            $canvas->page_text($width / 4, $height / 2, $doc->status, null, 25, [0, 0, 0], 2, 6, -20);
            return $pdf->stream('Market-AuditTrial' . $id . '.pdf');
        }
    }







    public function auditTrailPdf($id)
    {
        $doc = MarketComplaint::find($id);
        $doc->originator = User::where('id', $doc->initiator_id)->value('name');
        $data = MarketComplaintAuditTrial::where('market_id', $doc->id)->orderByDesc('id')->paginate();
        $pdf = App::make('dompdf.wrapper');
        $time = Carbon::now();
        $pdf = PDF::loadview('frontend.market_complaint.marketcomplaint_audit_trail_pdf', compact('data', 'doc'))
            ->setOptions([
                'defaultFont' => 'sans-serif',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'isPhpEnabled' => true,
            ]);
        $pdf->setPaper('A4');
        $pdf->render();
        $canvas = $pdf->getDomPDF()->getCanvas();
        $height = $canvas->get_height();
        $width = $canvas->get_width();

        $canvas->page_script('$pdf->set_opacity(0.1,"Multiply");');

        $canvas->page_text(
            $width / 3,
            $height / 2,
            $doc->status,
            null,
            60,
            [0, 0, 0],
            2,
            6,
            -20
        );
        return $pdf->stream('Market-Audit_Trail' . $id . '.pdf');
    }







}


