<!-- DataTables  & Plugins -->
<script src="<?php echo base_url($this->config->item("theme_admin") . "/plugins/datatables/jquery.dataTables.min.js"); ?>"></script>
<script src="<?php echo base_url($this->config->item("theme_admin") . "/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"); ?>"></script>
<script src="<?php echo base_url($this->config->item("theme_admin") . "/plugins/datatables-responsive/js/dataTables.responsive.min.js"); ?>"></script>
<script src="<?php echo base_url($this->config->item("theme_admin") . "/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"); ?>"></script>
<script src="<?php echo base_url($this->config->item("theme_admin") . "/plugins/datatables-buttons/js/dataTables.buttons.min.js"); ?>"></script>
<script src="<?php echo base_url($this->config->item("theme_admin") . "/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"); ?>"></script>
<script src="<?php echo base_url($this->config->item("theme_admin") . "/plugins/jszip/jszip.min.js"); ?>"></script>
<script src="<?php echo base_url($this->config->item("theme_admin") . "/plugins/pdfmake/pdfmake.min.js"); ?>"></script>
<script src="<?php echo base_url($this->config->item("theme_admin") . "/plugins/pdfmake/vfs_fonts.js"); ?>"></script>
<script src="<?php echo base_url($this->config->item("theme_admin") . "/plugins/datatables-buttons/js/buttons.html5.min.js"); ?>"></script>
<script src="<?php echo base_url($this->config->item("theme_admin") . "/plugins/datatables-buttons/js/buttons.print.min.js"); ?>"></script>
<script src="<?php echo base_url($this->config->item("theme_admin") . "/plugins/datatables-buttons/js/buttons.colVis.min.js"); ?>"></script>
<script>
  $(function() {
    $("#example1").DataTable({
      "responsive": true,
      "lengthChange": false,
      "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
  });
  //, "colvis"
</script>