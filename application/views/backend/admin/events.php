<?php $subdomain = $this->db->get_where('admin', array('admin_id' => $this->session->userdata('login_user_id')))->row()->subdomain;?>
<div class="content-w">
      <div class="os-tabs-w menu-shad">
        <div class="os-tabs-controls">
          <ul class="nav nav-tabs upper">
            <li class="nav-item">
              <a class="nav-link " href="<?php echo base_url();?>admin/news/"><i class="os-icon picons-thin-icon-thin-0010_newspaper_reading_news"></i><span><?php echo get_phrase('noticeboard');?></span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo base_url();?>admin/send_news/"><i class="os-icon picons-thin-icon-thin-0068_text_image_article_view"></i><span><?php echo get_phrase('add_news');?></span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link active" href="<?php echo base_url();?>admin/events/"><i class="os-icon picons-thin-icon-thin-0021_calendar_month_day_planner"></i><span><?php echo get_phrase('add_event');?></span></a>
            </li>
          </ul>
        </div>
      </div>
  <div class="content-i">
    <div class="content-box">
      <div class="element-box lined-primary shadow">
        <?php echo form_open(base_url() . 'admin/news/create_event/', array('enctype' => 'multipart/form-data')); ?>
          <h5 class="form-header"><?php echo get_phrase('add_event');?></h5>
          <div class="form-group">
            <label for=""> <?php echo get_phrase('title');?></label><input class="form-control" placeholder="" type="text" required="" name="title">
          </div>
		      <div class="form-group">
                <label> <?php echo get_phrase('description');?></label><textarea class="form-control" rows="4" required="" name="description"></textarea>
          </div>
		      <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label for=""> <?php echo get_phrase('from');?></label><input class="form-control" required="" name="from" id="datefrom" type="text">
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label for=""> <?php echo get_phrase('until');?></label><input class="form-control" name="to" id="dateto" required type="text">
                </div>
              </div>
            </div>
		  <div class="form-group">
			<label for=""> <?php echo get_phrase('featured_image');?></label>
			  <div class="newsfe" style="max-width:500px">
				<button type="button" class="change-cover">
					<i class="font-icon picons-thin-icon-thin-0617_picture_image_photo"></i>
					<?php echo get_phrase('upload');?>
					<input accept="image/x-png,image/gif,image/jpeg" id="imgpre" type="file"/ name="userfile">
				</button>
				<img width="100%" id="ava" src="<?php echo base_url().$subdomain;?>uploads/img_pre.jpg">
			</div>
			 </div>
          <div class="form-buttons-w">
            <button class="btn btn-rounded btn-primary" type="submit"> <?php echo get_phrase('save');?></button>
          </div>
        <?php echo form_close();?>
    </div>
	</div></div>
</div>
<script type="text/javascript">
$(document).ready(function () {
	 $('#dateto').daterangepicker({
        singleDatePicker: true,
		timePicker: false,
        showDropdowns: true,
		autoUpdateInput: true,
		minDate: moment().startOf('day'),
		locale: {
            format: 'DD/MM/YYYY'
        }
    });
});
</script>

<script type="text/javascript">
$(document).ready(function () {
    $('#datefrom').daterangepicker({
        singleDatePicker: true,
		timePicker: false,
        showDropdowns: true,
		minDate : moment().startOf('day'),
		locale: {
            format: 'DD/MM/YYYY'
        }
    });
});
</script>