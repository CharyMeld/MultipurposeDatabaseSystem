$(document).ready(function() {
	$('#verifyForm').submit(function(e){
			e.preventDefault();
			submitAjaxForm($(this));
	});

	let $base = $('#baseurl').val();
	$('#biodata-edit').click(function(event) {
		var biodataEdit = $('#biodata-form-section');
		var biodataTable = $('#biodata-table');
		if (biodataEdit.hasClass('d-none')) {
			$(this).html('<i class="fa fa-eye"></i> show data');
			biodataTable.addClass('d-none');
			biodataEdit.removeClass('d-none');
		}
		else{
			$(this).html('<i class="fa fa-edit"></i> edit');
			biodataEdit.addClass('d-none');
			biodataTable.removeClass('d-none');
		}
		
	});

	$('#academic-edit').click(function(event) {
		var academicEdit = $('#academic-form');
		var academicTable = $('#academic-table');
		if (academicEdit.hasClass('d-none')) {
			$(this).html('<i class="fa fa-eye"></i> show data');
			academicTable.addClass('d-none');
			academicEdit.removeClass('d-none');
		}
		else{
			$(this).html('<i class="fa fa-edit"></i> edit');
			academicEdit.addClass('d-none');
			academicTable.removeClass('d-none');
		}
		
	});

	$('#edit-parent').click(function(event) {
		var academicEdit = $('#parent-form-section');
		var academicTable = $('#parent-table-section');
		if (academicEdit.hasClass('d-none')) {
			$(this).html('<i class="fa fa-eye"></i> show data');
			academicTable.addClass('d-none');
			academicEdit.removeClass('d-none');
		}
		else{
			$(this).html('<i class="fa fa-edit"></i> edit');
			academicEdit.addClass('d-none');
			academicTable.removeClass('d-none');
		}
		
	});

	$('#parent-edit-form').submit(function(event) {
		event.preventDefault();
		var parentName = $('#parent-name').val();
		var parentPhone = $('#parent-phone').val();
		var parentEmail = $('#parent-email').val();
		var parentOccupation = $('#parent-occupation').val();
		var parentAddress = $('#parent-address').val();
		var jsonResult = {name:parentName,address:parentAddress,phone:parentPhone,email:parentEmail,occupation:parentOccupation};
		var resultString = JSON.stringify(jsonResult);
		var data = 'parent='+encodeURIComponent(resultString)+'&edu-submit=update';
		var link = $('#std_item').val();
		sendAjax($(this),link,data,'post');
	});


	//implementing interaction for sponsors
	$('#edit-sponsor').click(function(event) {
		var academicEdit = $('#sponsor-form-section');
		var academicTable = $('#sponsor-table-section');
		if (academicEdit.hasClass('d-none')) {
			$(this).html('<i class="fa fa-eye"></i> show data');
			academicTable.addClass('d-none');
			academicEdit.removeClass('d-none');
		}
		else{
			$(this).html('<i class="fa fa-edit"></i> edit');
			academicEdit.addClass('d-none');
			academicTable.removeClass('d-none');
		}
		
	});

	$('#sponsor-edit-form').submit(function(event) {
		event.preventDefault();
		var sponsorName = $('#sponsor-name').val();
		var sponsorPhone = $('#sponsor-phone').val();
		var sponsorEmail = $('#sponsor-email').val();
		var sponsorOccupation = $('#sponsor-occupation').val();
		var sponsorAddress = $('#sponsor-address').val();
		var jsonResult = {name:sponsorName,address:sponsorAddress,phone:sponsorPhone,email:sponsorEmail,occupation:sponsorOccupation};
		var resultString = JSON.stringify(jsonResult);
		var data = 'sponsor='+encodeURIComponent(resultString)+'&edu-submit=update';
		var link = $('#std_item').val();
		sendAjax($(this),link,data,'post');
	});


	//implementing interaction for next of kin
	$('#edit-nkin').click(function(event) {
		var academicEdit = $('#nkin-form-section');
		var academicTable = $('#nkin-table-section');
		if (academicEdit.hasClass('d-none')) {
			$(this).html('<i class="fa fa-eye"></i> show data');
			academicTable.addClass('d-none');
			academicEdit.removeClass('d-none');
		}
		else{
			$(this).html('<i class="fa fa-edit"></i> edit');
			academicEdit.addClass('d-none');
			academicTable.removeClass('d-none');
		}
		
	});

	$('#nkin-edit-form').submit(function(event) {
		event.preventDefault();
		var nkinName = $('#nkin-name').val();
		var nkinPhone = $('#nkin-phone').val();
		var nkinEmail = $('#nkin-email').val();
		var nkinRelationship = $('#nkin-relationship').val();
		var nkinAddress = $('#nkin-address').val();
		var jsonResult = {name:nkinName,address:nkinAddress,phone:nkinPhone,email:nkinEmail,relationship:nkinRelationship};
		var resultString = JSON.stringify(jsonResult);
		var data = 'next_of_kin='+encodeURIComponent(resultString)+'&edu-submit=update';
		var link = $('#std_item').val();
		sendAjax($(this),link,data,'post');
	});

	//implementing interaction social media section
	$('#edit-social').click(function(event) {
		var academicEdit = $('#social-form-section');
		var academicTable = $('#social-table-section');
		if (academicEdit.hasClass('d-none')) {
			$(this).html('<i class="fa fa-eye"></i> show data');
			academicTable.addClass('d-none');
			academicEdit.removeClass('d-none');
		}
		else{
			$(this).html('<i class="fa fa-edit"></i> edit');
			academicEdit.addClass('d-none');
			academicTable.removeClass('d-none');
		}
		
	});

	$('#social-edit-form').submit(function(event) {
		event.preventDefault();
		var facebook = $('#social-facebook').val();
		var instagram = $('#social-instagram').val();
		var linkedin = $('#social-linkedin').val();
		var twitter = $('#social-twitter').val();
		var jsonResult = {facebook:facebook,linkedin:linkedin,instagram:instagram,twitter:twitter};
		var resultString = JSON.stringify(jsonResult);
		var data = 'social_media='+encodeURIComponent(resultString)+'&edu-submit=update';
		var link = $('#std_item').val();
		sendAjax($(this),link,data,'post');
	});

	// implementing for the result section
	//implementing interaction for olevel
	$('#edit-olevel').click(function(event) {
		var academicEdit = $('#olevel-form-section');
		var academicTable = $('#olevel-table-section');
		if (academicEdit.hasClass('d-none')) {
			$(this).html('<i class="fa fa-eye"></i> show data');
			academicTable.addClass('d-none');
			academicEdit.removeClass('d-none');
		}
		else{
			$(this).html('<i class="fa fa-edit"></i> edit');
			academicEdit.addClass('d-none');
			academicTable.removeClass('d-none');
		}
		
	});

	$('#olevel-edit-form').submit(function(event) {
		event.preventDefault();
		var first= getOlevelSitting('first-sitting');
		var second = getOlevelSitting('second-sitting');
		var jsonResult = [first,second];
		var resultString = JSON.stringify(jsonResult);
		var data = 'olevel='+encodeURIComponent(resultString)+'&edu-submit=update';
		var link = $('#std_item').val();
		sendAjax($(this),link,data,'post');
	});


	$('#edit-utme').click(function(event) {
		var academicEdit = $('#utme-form-section');
		var academicTable = $('#utme-table-section');
		if (academicEdit.hasClass('d-none')) {
			$(this).html('<i class="fa fa-eye"></i> show data');
			academicTable.addClass('d-none');
			academicEdit.removeClass('d-none');
		}
		else{
			$(this).html('<i class="fa fa-edit"></i> edit');
			academicEdit.addClass('d-none');
			academicTable.removeClass('d-none');
		}
		
	});

	$('#utme-edit-form').submit(function(event) {
		event.preventDefault();
		var year = $('#utme-year').val();
		var registration = $('#utme-registration').val();
		var utmeScore = $('#utme-total-score').val();
		var subjectScores = getUtmeSubjects();
		var jsonResult = {exam_year:year,registration_number:registration,total_score:utmeScore,subject_scores:subjectScores};
		var resultString = JSON.stringify(jsonResult);
		var data = 'jamb='+encodeURIComponent(resultString)+'&edu-submit=update';
		var link = $('#std_item').val();
		sendAjax($(this),link,data,'post');
	});


//DE javascript controller

	$('#edit-de').click(function(event) {
		var academicEdit = $('#de-form-section');
		var academicTable = $('#de-table-section');
		if (academicEdit.hasClass('d-none')) {
			$(this).html('<i class="fa fa-eye"></i> show data');
			academicTable.addClass('d-none');
			academicEdit.removeClass('d-none');
		}
		else{
			$(this).html('<i class="fa fa-edit"></i> edit');
			academicEdit.addClass('d-none');
			academicTable.removeClass('d-none');
		}
		
	});

	$('#de-edit-form').submit(function(event) {
		event.preventDefault();
		var year = $('#de-year').val();
		var registration = $('#de-registration').val();
		var utmeScore = $('#de-total-score').val();
		var subjectScores = getUtmeSubjects();
		var jsonResult = {exam_year:year,registration_number:registration,total_score:utmeScore,subject_scores:subjectScores};
		var resultString = JSON.stringify(jsonResult);
		var data = 'DE='+encodeURIComponent(resultString)+'&edu-submit=update';
		var link = $('#std_item').val();
		sendAjax($(this),link,data,'post');
	});
	$('#edit-experience').click(function(event) {
		var academicEdit = $('#experience-form-section');
		var academicTable = $('#experience-table-section');
		if (academicEdit.hasClass('d-none')) {
			$(this).html('<i class="fa fa-eye"></i> show data');
			academicTable.addClass('d-none');
			academicEdit.removeClass('d-none');
		}
		else{
			$(this).html('<i class="fa fa-edit"></i> edit');
			academicEdit.addClass('d-none');
			academicTable.removeClass('d-none');
		}
		
	});

	$('#experience-edit-form').submit(function(event) {
		event.preventDefault();
		var company = $('#company').val();
		var business_address = $('#business_address').val();
		var job_role = $('#job_role').val();
		var phone = $('#job_phone').val();
		var start_date = $('#exp-start-date').val();
		var end_date = $('#exp-end-date').val();
		var jsonResult = {company:company,business_address:business_address,job_role:job_role,start_date:start_date,end_date:end_date,phone:phone};
		var resultString = JSON.stringify(jsonResult);
		var data = 'work_experience='+encodeURIComponent(resultString)+'&edu-submit=update';
		var link = $('#std_item').val();
		sendAjax($(this),link,data,'post');
	});
	
	$('#edit-business').click(function(event) {
		var academicEdit = $('#business-form-section');
		var academicTable = $('#business-table-section');
		if (academicEdit.hasClass('d-none')) {
			$(this).html('<i class="fa fa-eye"></i> show data');
			academicTable.addClass('d-none');
			academicEdit.removeClass('d-none');
		}
		else{
			$(this).html('<i class="fa fa-edit"></i> edit');
			academicEdit.addClass('d-none');
			academicTable.removeClass('d-none');
		}
		
	});

	$('#business-edit-form').submit(function(event) {
		event.preventDefault();
		var business = $('#busi_name').val();
		var bAddress = $('#busi_address').val();
		var bPhone = $('#business_phone').val();
		var jsonResult = {business:business,address:bAddress,phone:bPhone};
		var resultString = JSON.stringify(jsonResult);
		var data = 'business='+encodeURIComponent(resultString)+'&edu-submit=update';
		var link = $('#std_item').val();
		sendAjax($(this),link,data,'post');
	});

// for institution attended
	$('#edit-institution').click(function(event) {
		var academicEdit = $('#institution-form-section');
		var academicTable = $('#institution-table-section');
		if (academicEdit.hasClass('d-none')) {
			$(this).html('<i class="fa fa-eye"></i> show data');
			academicTable.addClass('d-none');
			academicEdit.removeClass('d-none');
		}
		else{
			$(this).html('<i class="fa fa-edit"></i> edit');
			academicEdit.addClass('d-none');
			academicTable.removeClass('d-none');
		}
		
	});

	$('#institution-edit-form').submit(function(event) {
		event.preventDefault();
		jsonResult = [];
		for (var i = 0; i <= 3; i++) {
			var cc = i+1
			var inst = $('#institution'+cc).val();
			var cld = $('#class_of_degree'+cc).val();
			var grad = $('#graduation'+cc).val();
			jsonResult.push({institution_attended:inst,class_of_degree:cld,year_of_graduation:grad});
		}
		
		var resultString = JSON.stringify(jsonResult);
		var data = 'institution_attended='+encodeURIComponent(resultString)+'&edu-submit=update';
		var link = $('#std_item').val();
		sendAjax($(this),link,data,'post');
	});
	// for institution attended
	$('#edit-phd').click(function(event) {
		var academicEdit = $('#phd-form-section');
		var academicTable = $('#phd-table-section');
		if (academicEdit.hasClass('d-none')) {
			$(this).html('<i class="fa fa-eye"></i> show data');
			academicTable.addClass('d-none');
			academicEdit.removeClass('d-none');
		}
		else{
			$(this).html('<i class="fa fa-edit"></i> edit');
			academicEdit.addClass('d-none');
			academicTable.removeClass('d-none');
		}

	});

	$('#phd-edit-form').submit(function(event) {
		event.preventDefault();
		var inputs =$(this).find('input');
		jsonResult = {};
		// if(ids.indexOf('[')!==-1)
		inputs.each(function(index, el) {
			var ids = $(this).attr('id');
			if (ids.indexOf('-')!==-1) {
				var names = ids.split('-');
				if (!jsonResult[names[0]]) {
					jsonResult[names[0]]={};
				}
				let tempUpdate;
				let lastKey='';
				if (names.length==3) {
					if (!jsonResult[names[0]][names[1]]) {
						jsonResult[names[0]][names[1]]={};
					}
					tempUpdate = jsonResult[names[0]][names[1]];
					lastKey = names[2];
					// jsonResult[names[0]][names[1]][names[2]]=$(this).val();
				}
				else{
					tempUpdate = jsonResult[names[0]];
					lastKey = names[1];
					// jsonResult[names[0]][names[1]]=$(this).val();
				}
				let index = lastKey.indexOf('[');
				if (index===-1) {
					tempUpdate[lastKey]=$(this).val();
				}
				else{
					let key = lastKey.substring(0,index);
					if (!tempUpdate[key]) {
						tempUpdate[key]=[];
					}
					tempUpdate[key].push($(this).val());
				}
				// console.log(tempUpdate);
				// console.log(jsonResult);

			}
			else  if (ids.indexOf('[')!==-1) {
				var index = ids.indexOf('[');
				var realName = ids.substring(0,index);
				if (!jsonResult[realName]) {
					jsonResult[realName]=[];
				}
				jsonResult[realName].push($(this).val());
			}
			else {
				jsonResult[ids]=$(this).val();
			}

		});
		var resultString = JSON.stringify(jsonResult);
		var data = 'phd_details='+encodeURIComponent(resultString)+'&edu-submit=update';
		var link = $('#std_item').val();
		sendAjax($(this),link,data,'post');
	});
});


function getUtmeSubjects() {
	var result ={};
	var utme = $('.jamb_score').each(function(index, el) {
		var subj = $(this).find('.subject').val();
		var score = $(this).find('.score').val();
		if (subj.trim() && score.trim()) {
			result[subj.trim()]=score.trim();
		}
	});
	return result;
}

function getOlevelSitting(id) {
	var sitting = $('#'+id);
	var exam_type = sitting.find('.exam_type').val();
	var exam_number = sitting.find('.exam_number').val();
	var exam_month = sitting.find('.exam_month').val();
	var exam_year = sitting.find('.exam_year').val();
	exam_type = exam_type?exam_type:'';
	exam_number = exam_number?exam_number:'';
	exam_month = exam_month?exam_month:'';
	exam_year = exam_year?exam_year:'';
	// if (!(exam_number && exam_month && exam_year && exam_type)) {
	// 	return false;
	// }
	var temp ={};
	var tt = sitting.find('.olevel_score');
	tt.each(function(index, el) {
		var subj = $(this).find('.subject').val();
		var grade = $(this).find('.grade').val();
		if (subj.trim()) {
			subj = subj.trim();
			grade = grade.trim();
			temp[subj]=grade;
		}
		
	});
	var result = {exam_type:exam_type,exam_number:exam_number,exam_month:exam_month,exam_year:exam_year,scores:temp};
	return result;
}

function ajaxFormSuccess(target, data) {
	if (data.status) {
		reportAndRefresh(target,data);
		return;
	}
	else{
		showNotification(false,data.message);
	}
}