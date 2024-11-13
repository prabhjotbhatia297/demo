<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('non_conformances', function (Blueprint $table) {
            $table->id();
            $table->integer('initiator_id')->nullable();
            $table->integer('record')->nullable();
            $table->string('division_id')->nullable();
            //$table->string('division_code')->nullable();
            $table->string('initiator_group_code')->nullable();
            $table->date('intiation_date')->nullable();
            $table->string('form_type')->nullable();
            $table->integer('record_number')->nullable();
            //$table->string('text')->nullable();
            $table->integer('assign_to')->nullable();
            $table->text('due_date')->nullable();
            $table->text('non_conformances_time')->nullable();
            $table->string('Initiator_Group')->nullable();
            $table->longText('short_description')->nullable();
            $table->string('short_description_required')->nullable();
            $table->string('nature_of_repeat')->nullable();
            $table->date('non_conformances_date')->nullable();
            $table->date('non_conformances_reported_date')->nullable();
            $table->string('Facility')->nullable();
            $table->string('Capachild')->nullable();
            $table->string('Rootchild')->nullable();
            $table->string('effectivenesschild')->nullable();
            $table->string('Changecontrolchild')->nullable();
            $table->string('actionchild')->nullable();
            $table->string('Extensionchild')->nullable();
            $table->string('audit_type')->nullable();
            $table->longText('others')->nullable();
            $table->string('Facility_Equipment')->nullable();
            $table->string('Document_Details_Required')->nullable();
            $table->longText('Product_Batch')->nullable();
            $table->longText('Description_non_conformanceS')->nullable();
            $table->text('Related_Records1')->nullable();
            $table->longText('Immediate_Action')->nullable();
            $table->longText('Preliminary_Impact')->nullable();
            $table->longText('Product_Details_Required')->nullable();
            $table->longText('HOD_Remarks')->nullable();
            $table->string('non_conformances_category')->nullable();
            $table->longText('Justification_for_categorization')->nullable();
            $table->string('Investigation_required')->nullable();
            $table->longText('Investigation_Details')->nullable();
            $table->string('Customer_notification')->nullable();
            $table->string('customers')->nullable();
            $table->string('non_conformances_id')->nullable();
            $table->longText('QAInitialRemark')->nullable();
           
            $table->longText('Investigation_Summary')->nullable();
            $table->longText('Impact_assessment')->nullable();
            $table->longText('Root_cause')->nullable();
            $table->string('CAPA_Rquired')->nullable();
            $table->string('capa_type')->nullable();
            $table->longText('CAPA_Description')->nullable();
            $table->longText('Post_Categorization')->nullable();
            $table->longText('Investigation_Of_Review')->nullable();
            $table->longText('QA_Feedbacks')->nullable();
            $table->longText('Closure_Comments')->nullable();
            $table->longText('Disposition_Batch')->nullable();
            $table->longText('Audit_file')->nullable();
            $table->longText('Initial_attachment')->nullable();
            $table->longText('QA_attachment')->nullable();
            $table->longText('Investigation_attachment')->nullable();
            $table->longText('Capa_attachment')->nullable();
            $table->longText('QA_attachments')->nullable();
            $table->longText('closure_attachment')->nullable();

            $table->string('submit_on')->nullable();
            $table->string('submit_by')->nullable();
            $table->longText('submit_comment')->nullable();

            $table->string('HOD_Review_Complete_By')->nullable();
            $table->string('HOD_Review_Complete_On')->nullable();
            $table->longText('HOD_Review_Comments')->nullable();

            $table->string('QA_Initial_Review_Complete_By')->nullable();
            $table->string('QA_Initial_Review_Complete_On')->nullable();
            $table->longText('QA_Initial_Review_Comments')->nullable();

            $table->string('QA_Final_Review_Complete_By')->nullable();
            $table->string('QA_Final_Review_Complete_On')->nullable();
            $table->longText('QA_Final_Review_Comments')->nullable();
            
            $table->string('CFT_Review_Complete_By')->nullable();
            $table->string('CFT_Review_Complete_On')->nullable();
            $table->longText('CFT_Review_Comments')->nullable();
             
            $table->string('qa_more_info_required_by')->nullable();
            $table->string('qa_more_info_required_on')->nullable();
            $table->string('Approved_By')->nullable();
            $table->string('Approved_On')->nullable();
            $table->longText('Approved_Comments')->nullable();

            $table->text('QA_head_approved_by')->nullable();
            $table->text('QA_head_approved_on')->nullable();
            $table->longText('QA_head_approved_comment')->nullable();
            
            $table->text('pending_initiator_approved_by')->nullable();
            $table->text('pending_initiator_approved_on')->nullable();
            $table->longText('pending_initiator_approved_comment')->nullable();
            
            $table->text('QA_final_approved_by')->nullable();
            $table->text('QA_final_approved_on')->nullable();
            $table->longText('QA_final_approved_comment')->nullable();

            $table->string('capa_required')->nullable();
            $table->string('qrm_required')->nullable();

            $table->string('Conclusion')->nullable();
            $table->string('Identified_Risk')->nullable();
            $table->string('severity_rate')->nullable();
            $table->string('Occurrence')->nullable();
            $table->string('detection')->nullable();

            $table->string('cancelled_on')->nullable();
            $table->string('cancelled_by')->nullable();
            $table->string('rejected_on')->nullable();
            $table->string('rejected_by')->nullable();
            $table->string('Delay_Justification')->nullable();
            $table->string('Discription_Event')->nullable();
            $table->string('objective')->nullable();
            $table->string('scope')->nullable();
            $table->string('imidiate_action')->nullable();
            $table->string('investigation_approach')->nullable();

            $table->longtext('attention_issues')->nullable();
            $table->longtext('attention_actions')->nullable();
            $table->longtext('attention_remarks')->nullable();

            $table->longtext('understanding_issues')->nullable();
            $table->longtext('understanding_actions')->nullable();
            $table->longtext('understanding_remarks')->nullable();

            $table->longtext('procedural_issues')->nullable();
            $table->longtext('procedural_actions')->nullable();
            $table->longtext('procedural_remarks')->nullable();

            $table->longtext('behavioiral_issues')->nullable();
            $table->longtext('behavioiral_actions')->nullable();
            $table->longtext('behavioiral_remarks')->nullable();

            $table->longtext('skill_issues')->nullable();
            $table->longtext('skill_actions')->nullable();
            $table->longtext('skill_remarks')->nullable();

            $table->longtext('what_will_be')->nullable();
            $table->longtext('what_will_not_be')->nullable();
            $table->longtext('what_rationable')->nullable();
            $table->longtext('where_will_be')->nullable();
            $table->longtext('where_will_not_be')->nullable();
            $table->longtext('where_rationable')->nullable();
            $table->longtext('when_will_be')->nullable();
            $table->longtext('when_will_not_be')->nullable();
            $table->longtext('when_rationable')->nullable();
            $table->longtext('coverage_will_be')->nullable();
            $table->longtext('coverage_will_not_be')->nullable();
            $table->longtext('coverage_rationable')->nullable();
            $table->longtext('who_will_be')->nullable();
            $table->longtext('who_will_not_be')->nullable();
            $table->longtext('who_rationable')->nullable();

            $table->string('status')->nullable();
            $table->integer('stage')->nullable();

            $table->string('form_progress')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('non_conformances');
    }
};
