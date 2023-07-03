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
               
			   <hr><br>
			</div>			
		</div> 

<div class="modal fade" id="editModal" tabindex="-1" role="dialog" style="margin-top:100px;" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Calendar Event</h4>
      </div>
      <div class="modal-body">
      
      <div class="form-group">
                <label for="p-in" class="col-md-4 label-heading">Event Name</label>
                <div class="col-md-8 ui-front">
                    <input type="text" class="form-control" name="name" value="" id="name">
                </div>
        </div>
        <div class="form-group">
                <label for="p-in" class="col-md-4 label-heading">Description</label>
                <div class="col-md-8 ui-front">
                    <input type="text" class="form-control" name="description" id="description">
                </div>
        </div>        
            <input type="hidden" name="eventid" id="event_id" value="0" />
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
                    url: '<?php echo base_url();?>student/calendar_get_events',
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

      
       eventClick: function(event, jsEvent, view) {
          $('#name').val(event.title);
          $('#description').val(event.description);
          $('#start_date').val(moment(event.start).format("YYYY/MM/DD HH:mm"));
          if(event.end) {
            $('#end_date').val(moment(event.end).format("YYYY/MM/DD HH:mm"));
          } else {
            $('#end_date').val(moment(event.start).format("YYYY/MM/DD HH:mm"));
          }
          $('#event_id').val(event.id);
		  $('#event_color').val(event.color);
          $('#editModal').modal(); 
       },
	   
    });
});




</script>
    </body>
</html>