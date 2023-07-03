     <head>

        <!--<link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />-->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	
	<style>
			
			
	 h4{
		color: white !important;
	}
		  </style>
	</head>	

<div class="content-w">
  <ul class="breadcrumb hidden-xs-down hidden-sm-down">
            <div class="logoutleft">
                <h6><?php echo $this->db->get_where('settings', array('type' => 'system_title'))->row()->description;?></h6>
            </div>
            <div class="logout"><a href="<?php echo base_url();?>login/logout"><span><?php echo get_phrase('logout');?></span><i class="os-icon picons-thin-icon-thin-0040_exit_logout_door_emergency_outside"></i></a></div>
   </ul>
	<div class="logoutleft">
                <h6></h6>
     </div>
	 <div> <h6></h6> <h6></h6> </div>
	 <ul class="breadcrumb hidden-xs-down hidden-sm-down">
		 <div class="row">
							<div class="col-sm-2">
							    <div class="form-group row">
								    <span class="greendot" ></span>
									<label for="p-in" class="col-md-4 label-heading"><h6>Days off</h6></label>
								</div>	
							</div>
							
							<div class="col-sm-2">
							    <div class="form-group row">
								    <span class="reddot" ></span>
									<label for="p-in" class="col-md-4 label-heading"><h6>Exams</h6></label>
								</div>	
							</div>
							
							<div class="col-sm-2">
							    <div class="form-group row">
								    <span class="bluedot" ></span>
									<label for="p-in" class="col-md-4 label-heading"><h6>Travel</h6></label>
								</div>	
							</div>
							
							<div class="col-sm-2">
							    <div class="form-group row">
								    <span class="blackdot" ></span>
									<label for="p-in" class="col-md-4 label-heading"><h6>Field Trips</h6></label>
								</div>	
							</div>
							
							<div class="col-sm-2">
							    <div class="form-group row">
								    <span class="magentadot" ></span>
									<label for="p-in" class="col-md-4 label-heading"><h6>Class Breaks</h6></label>
								</div>	
							</div>
							
							<div class="col-sm-2">
							    <div class="form-group row">
								    <span class="cyandot" ></span>
									<label for="p-in" class="col-md-4 label-heading"><h6>Others</h6></label>
								</div>	
							</div>		
							
		</div>
	</ul>	
	<div class="content-i">
            <div class="content-box">
               <div id="calendar"></div>
			   <div id='datepicker'></div>
	        </div>			
</div> 
		
			  
				   
					<div aria-hidden="true" class="modal fade" id="addModal" tabindex="-1" role="dialog"  style="margin-top:100px;" aria-labelledby="exampleModalLabel">
					  <div class="modal-dialog" role="document">
						<div class="modal-content">
						  <div class="modal-header">
							<h4 class="modal-title" id="exampleModalLabel">Add Calendar Event</h4>
							<button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true"> &times;</span></button>
						  </div>
						  <div class="modal-body">
						  <?php echo form_open(  base_url().'admin/calendar_add_event', array("class" => "form-group")); ?>

						     <div class="form-group row">
									<label for="p-in" class="col-md-3 label-heading">Event Name</label>
									<div class="col-md-8 ui-front">
										<input type="text" class="form-control" name="name" value="" required>
									</div>
							  </div>		


							   <div class="form-group row">
									<label for="p-in" class="col-md-3 label-heading">Description</label>
									<div class="col-md-8 ui-front">
										<input type="text" class="form-control" name="description" required>
									</div>
								</div>	


							   <div class="form-group row">
							        <label for="p-in" class="col-md-3 label-heading">Category</label>
									<div class="form-group col-sm-8" >
											  <div class="input-group">
											  <div class="input-group-addon">
												<i class="picons-thin-icon-thin-0307_chat_discussion_yes_no_pro_contra_conversation"></i>
											  </div>
											  <select class="form-control" name="event_color"   required="">
													<option value="">Select</option>
													<option value="green">Days Off</option>
													<option value="red">Exams</option>
													<option value="blue">Travel</option>
													<option value="black">Field Trips</option>
													<option value="magenta">Class Breaks</option>
													<option value="cyan">Others</option>
											  </select>
											</div>
									</div>
								</div>	
							
							
									<div class="form-group row">
									  <label for="p-in" class="col-md-3 label-heading">Start Date</label>
									  <div class="col-sm-8">
									   <div class="input-group">
									   <div class="input-group-addon">
										  <i class="picons-thin-icon-thin-0023_calendar_month_day_planner_events"></i>
									   </div>
									   <input class="single-daterange-2 form-control" name="start_date" id="start_date_add" placeholder="Select Start Date" type="text" value="" required>
									   </div>
									  </div>
									</div>

						
									<div class="form-group row">
									  <label for="p-in" class="col-md-3 label-heading">End Date</label>
									  <div class="col-sm-8">
									   <div class="input-group">
									   <div class="input-group-addon">
										  <i class="picons-thin-icon-thin-0023_calendar_month_day_planner_events"></i>
									   </div>
									   <input class="single-daterange-2 form-control" name="end_date" id="end_date_add" placeholder="Select Start Date" type="text" value="" required>
									   </div>
									  </div>
									</div>
														
						 </div>	
						  <div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							<input type="submit" class="btn btn-primary" value="Add Event">
							<?php echo form_close() ?>
						  </div>
						
					  </div>
					</div>
				 </div>
					
			   
		
			
			
			
			
			
			
<div aria-hidden="true" aria-labelledby="exampleModalLabel2" class="modal fade" id="editModal" tabindex="-1" style="margin-top:100px;" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="exampleModalLabel2">Update Calendar Event</h4>
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
      <?php echo form_open(  base_url().'admin/calendar_edit_event', array("class" => "form-horizontal")); ?>
       <div class="form-group">
						     <div class="form-group row">
									<label for="p-in" class="col-md-3 label-heading">Event Name</label>
									<div class="col-md-8 ui-front">
										<input type="text" class="form-control" id="name" name="name" value="" required>
									</div>
							  </div>		
							</div>
							<div class="form-group">
							   <div class="form-group row">
									<label for="p-in" class="col-md-3 label-heading">Description</label>
									<div class="col-md-8 ui-front">
										<input type="text" class="form-control"  id="description" name="description" required>
									</div>
								</div>	
							</div>
							<div class="form-group">
							   <div class="form-group row">
							        <label for="p-in" class="col-md-3 label-heading">Category</label>
									<div class="form-group col-sm-8" >
											  <div class="input-group">
											  <div class="input-group-addon">
												<i class="picons-thin-icon-thin-0307_chat_discussion_yes_no_pro_contra_conversation"></i>
											  </div>
											  <select class="form-control" id="event_color" name="event_color" required="">
													<option value="">Select</option>
													<option value="green">Days Off</option>
													<option value="red">Exams</option>
													<option value="blue">Travel</option>
													<option value="black">Field Trips</option>
													<option value="magenta">Class Breaks</option>
													<option value="cyan">Others</option>
											  </select>
											</div>
									</div>
								</div>	
							</div>
							<div class="form-group">							
									<div class="form-group row">
									  <label for="p-in" class="col-md-3 label-heading">Start Date</label>
									  <div class="col-sm-8">
									   <div class="input-group">
									   <div class="input-group-addon">
										  <i class="picons-thin-icon-thin-0023_calendar_month_day_planner_events"></i>
									   </div>
									   <input class="single-daterange-2 form-control" name="start_date" id="start_date" placeholder="Select Start Date" type="text" value="" required>
									   </div>
									  </div>
									</div>
							</div>	
                            <div class="form-group">							
									<div class="form-group row">
									  <label for="p-in" class="col-md-3 label-heading">End Date</label>
									  <div class="col-sm-8">
									   <div class="input-group">
									   <div class="input-group-addon">
										  <i class="picons-thin-icon-thin-0023_calendar_month_day_planner_events"></i>
									   </div>
									   <input class="single-daterange-2 form-control" name="end_date" id="end_date" placeholder="Select Start Date" type="text" value="" required>
									   </div>
									  </div>
									</div>
							</div>	
        <div class="form-group">
                    
                    <div class="col-sd-3">
                          <label for="p-in" class="col-md-4 label-heading">Delete Event</label> <input class="form-check-input" type="checkbox" name="delete" id="delete">
                    </div>
            </div>
            <input type="hidden" name="eventid" id="eventid" value='0'/>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default"  data-dismiss="modal" >Close</button>
        <input type="submit" class="btn btn-primary" value="Update Event">
        <?php echo form_close() ?>
      </div>
    </div>
  </div>
</div>			
	</div>   


<script type="text/javascript">
$(document).ready(function() {
    var date_last_clicked = null;
    $('#calendar').fullCalendar({
		
		 header: {
        left: 'prev,next today',
        center: 'title',
        right: 'month,agendaWeek,agendaDay'
      },
      navLinks: true, // can click day/week names to navigate views
      selectable: true,
      selectHelper: true,
        eventSources: [
           {
           events: function(start, end, timezone,callback) {
                $.ajax({
                    url: '<?php echo base_url();?>admin/calendar_get_events',
                    dataType: 'json',
                    data: {
                        // our hypothetical feed requires UNIX timestamps
                        start: start.unix(),
                        end: end.unix()
                    },
                    success: function(msg) {
                        var events = msg.events;
                        callback(events);
                    },
					error: function() {
                    alert('There was an error while fetching events.')}
                });
              }
            },
        ],
        dayClick: function(date, jsEvent, view) {
            date_last_clicked = $(this);
			$('#start_date_add').val(moment(date).format("YYYY-MM-DD HH:mm"));
			$('#end_date_add').val(moment(date).format("YYYY-MM-DD HH:mm"));
            $(this).css('background-color', '#bed7f3');
            $('#addModal').modal('show');
        },

      
       eventClick: function(event, jsEvent, view) {
          $('#name').val(event.title);
          $('#description').val(event.description);
          $('#start_date').val(moment(event.start).format("YYYY-MM-DD HH:mm"));
          if(event.end) {
            $('#end_date').val(moment(event.end).format("YYYY-MM-DD HH:mm"));
          } else {
            $('#end_date').val(moment(event.start).format("YYYY-MM-DD HH:mm"));
          }
          $('#eventid').val(event.id);
		  $('#event_color').val(event.color);
		  $('#delete').prop("checked", false);
          $('#editModal').modal('show'); 
       },
	   
    });
});




</script>
    