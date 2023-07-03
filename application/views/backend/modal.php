    <script type="text/javascript">
	function showAjaxModal(url)
	{
		jQuery('#exampleModal .modal-body').html('<div style="text-align:center;margin-top:200px;"><img src="<?php echo base_url();?>assets/images/preloader.gif" /></div>');
		jQuery('#exampleModal').modal('show', {backdrop: 'true'});
		$.ajax({
			url: url,
			success: function(response)
			{
				jQuery('#exampleModal .modal-body').html(response);
			}
		});
	}
	</script>
    
    <div aria-hidden="true" aria-labelledby="myLargeModalLabel" class="modal fade bd-example-modal-lg" role="dialog" tabindex="-1" id="exampleModal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" style="margin-top:250px;"> 
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><?php echo $this->db->get_where('settings', array('type' => 'system_title'))->row()->description;?></h5>
                    <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true"> &times;</span></button>
                </div>        
                <div class="modal-body">
                </div>
            </div>
        </div>
    </div>


<style>
  .datepicker{z-index:1151 !important;}
</style>