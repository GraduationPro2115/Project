<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Admin | Dashboard</title>
  <?php $this->load->view("admin/common/common_css"); ?>
  <?php echo link_tag($this->config->item('theme_admin') . '/plugins/chosen/chosen.min.css'); ?>
  <link rel="stylesheet" href="<?php echo base_url($this->config->item("theme_admin")); ?>/plugins/richtexteditor/rte_theme_default.css">
</head>

<body class="hold-transition skin-blue sidebar-mini">
  <div class="wrapper">

    <?php $this->load->view("admin/common/common_header"); ?>
    <!-- Left side column. contains the logo and sidebar -->
    <?php $this->load->view("admin/common/common_sidebar"); ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1><?php echo $this->lang->line("Clinic"); ?></h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#"><?php echo $this->lang->line("Clinic"); ?></a></li>
                <li class="breadcrumb-item active"><?php echo $this->lang->line("Edit"); ?></li>
              </ol>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">

          <div class="">
            <form method="post" action="" enctype="multipart/form-data">
              <div class="">
                <?php if (isset($error)) {
                  echo $error;
                }
                echo $this->session->flashdata('message'); ?>
                <!-- general form elements -->
                <div class="card card-primary">

                  <!-- form start -->

                  <div class="card-body">

                    <div class="row">
                      <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                          <label for="bus_title"> <?php echo $this->lang->line("Clinic Title"); ?></label>
                          <input type="text" name="bus_title" id="bus_title" required="" value="<?php echo $setbuss->bus_title; ?>" class="form-control input-sm" placeholder="<?php echo $this->lang->line("Clinic Title"); ?>" data-toggle="floatLabel" data-value="no-js" />

                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-xs-12 col-sm-6 col-md-6">
                        <div class="form-group">
                          <label for="email"> <?php echo $this->lang->line("Email"); ?></label>
                          <input type="email" name="bus_email" id="email" class="form-control input-sm" required="" placeholder="<?php echo $this->lang->line("Email"); ?>" data-toggle="floatLabel" data-value="no-js" value="<?php echo $setbuss->bus_email; ?>" />
                        </div>
                      </div>
                      <div class="col-xs-12 col-sm-6 col-md-6">
                        <div class="form-group">
                          <label for="phone"><?php echo $this->lang->line("Contact No"); ?></label>
                          <input type="text" name="bus_phone" id="phone" class="form-control input-sm" required="" placeholder="<?php echo $this->lang->line("Contact No"); ?>" data-toggle="floatLabel" data-value="no-js" value="<?php echo $setbuss->bus_contact; ?>" />
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-xs-12 col-sm-6 col-md-6">
                        <div class="form-group">
                          <label for="bus_fee"> <?php echo $this->lang->line("Counsltant Fee"); ?></label>
                          <input type="number" name="bus_fee" id="bus_fee" class="form-control input-sm" required="" placeholder="00.00" data-toggle="floatLabel" data-value="no-js" value="<?php echo $setbuss->bus_fee; ?>" />
                        </div>
                      </div>
                      <div class="col-xs-12 col-sm-6 col-md-6">
                        <div class="form-group">
                          <label for="bus_con_time"> <?php echo $this->lang->line("Counsltant Time"); ?></label>
                          <?php $times = array("5" => "00:05:00", "10" => "00:10:00", "15" => "00:15:00", "20" => "00:20:00", "25" => "00:25:00", "30" => "00:30:00"); ?>
                          <select class="form-control input-sm" name="bus_con_time">
                            <?php foreach ($times as $key => $time) {
                            ?>
                              <option value="<?php echo $time; ?>" <?php if (strtotime($time) == strtotime($setbuss->bus_con_time)) {
                                                                      echo "selected";
                                                                    } ?>><?php echo $key; ?></option>
                            <?php
                            } ?>

                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="row">

                      <div class="col-xs-6 col-sm-6 col-md-6">
                        <div style="" class="form-group">
                          <label class=""> <?php echo $this->lang->line("Choose a Category"); ?></label>
                          <select data-placeholder="<?php echo $this->lang->line("Choose a Category"); ?>" class="form-control chosen-select" multiple style="width:350px;" name="buscat[]">
                            <option value=""></option>
                            <?php foreach ($categor as $categ) { ?>
                              <option value="<?php echo $categ->id; ?>" <?php foreach ($buscat as $bcat) {
                                                                          if ($bcat->category_id == $categ->id) {
                                                                            echo 'selected=""';
                                                                          }
                                                                        } ?>><?php for ($i = 0; $i < $categ->leval; $i++) {
                                                                                                                                                                          echo "- ";
                                                                                                                                                                        }
                                                                                                                                                                        echo $categ->title; ?></option>
                            <?php } ?>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-3">
                        <div class="bus-product">
                          <?php
                          $proicon = base_url($this->config->item('theme_folder') . "/images/generic-profile.png");
                          if ($setbuss->bus_logo != "") {
                            $proicon = base_url("uploads/business/" . $setbuss->bus_logo);
                          } ?>
                          <img src="<?php echo $proicon; ?>" style="height: 80px; width: 80px;" />
                        </div>
                      </div>
                      <div class="col-sm-3">
                        <div class="form-group">
                          <input type="file" name="bus_logo" value="<?php $setbuss->bus_logo; ?>" />
                        </div>
                      </div>
                    </div>
                    <hr />
                    <div class="row">
                      <div class="col-xs-12 col-sm-4 col-md-4">
                        <div class="form-group">
                          <label for="country_id"> <?php echo $this->lang->line("Country"); ?></label>
                          <select class="form-control select2" name="country_id" id="country_id" style="width: 100%;">
                            <option value=""> <?php echo $this->lang->line("Choose Country"); ?></option>
                            <?php foreach ($countries as $country) {
                            ?>
                              <option value="<?php echo $country->country_id; ?>" <?php if ($country->country_id == $setbuss->country_id) {
                                                                                    echo "selected";
                                                                                  } ?>><?php echo $country->country_name; ?></option>
                            <?php
                            } ?>
                          </select>
                        </div>
                      </div>
                      <div class="col-xs-12 col-sm-4 col-md-4">
                        <div class="form-group">
                          <label for="city_id"> <?php echo $this->lang->line("City"); ?></label>
                          <select class="form-control select2" name="city_id" id="city_id" style="width: 100%;">
                            <option value=""><?php echo $this->lang->line("Choose City "); ?></option>
                            <?php foreach ($cities as $city) {
                            ?>
                              <option value="<?php echo $city->city_id; ?>" <?php if ($city->city_id == $setbuss->city_id) {
                                                                              echo "selected";
                                                                            } ?>><?php echo $city->city_name; ?></option>
                            <?php
                            } ?>
                          </select>
                        </div>
                      </div>
                      <div class="col-xs-12 col-sm-4 col-md-4">
                        <div class="form-group">
                          <label for="locality_id"> <?php echo $this->lang->line("Locality"); ?></label>
                          <select class="form-control select2" name="locality_id" id="locality_id" style="width: 100%;">
                            <option value=""> <?php echo $this->lang->line("Choose Locality"); ?></option>
                            <?php foreach ($localities as $locality) {
                            ?>
                              <option value="<?php echo $locality->locality_id; ?>" <?php if ($locality->locality_id == $setbuss->locality_id) {
                                                                                      echo "selected";
                                                                                    } ?>><?php echo $locality->locality; ?></option>
                            <?php
                            } ?>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <label> <?php echo $this->lang->line("Clinic Address :"); ?></label>
                      <input type="text" name="address" value="<?php echo $setbuss->bus_google_street; ?>" class="form-control input-sm" id="latLog" placeholder="<?php echo $this->lang->line("Clinic Address :"); ?>" />

                    </div>

                    <div class="form-group">
                      <label> <?php echo $this->lang->line("Clinic Location :"); ?></label>
                      <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <div class="form-group">
                            <label for="lat"> <?php echo $this->lang->line("Latitude"); ?></label>
                            <input type="text" name="lat" id="lat" class="form-control input-sm" required="" placeholder="<?php echo $this->lang->line("Latitude"); ?>" data-toggle="floatLabel" data-value="no-js" value="<?php echo $setbuss->bus_latitude; ?>" />
                          </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <div class="form-group">
                            <label for="lon"> <?php echo $this->lang->line("Longitude"); ?></label>
                            <input type="text" name="lon" id="lon" class="form-control input-sm" required="" placeholder="<?php echo $this->lang->line("Longitude"); ?>" data-toggle="floatLabel" data-value="no-js" value="<?php echo $setbuss->bus_longitude; ?>" />
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <label> <?php echo $this->lang->line("Clinic Details :"); ?></label>
                      <textarea id="editor1" class="form-control" name="busdesc" placeholder="<?php echo $this->lang->line("Clinic Details :"); ?>" style="height:100px ;"><?php echo $setbuss->bus_description; ?></textarea>
                    </div>
                    <input type="submit" name="savebus" value="<?php echo $this->lang->line("Save"); ?>" class="btn btn-info btn-block" />

                  </div>


                </div><!-- /.box -->
              </div>

            </form>

          </div>
        </div>
      </section><!-- /.content -->
    </div><!-- /.content-wrapper -->

    <?php $this->load->view("admin/common/common_footer"); ?>

    <?php $this->load->view("admin/common/common_js"); ?>

    <script>
      function readURL(input) {
        if (input.files && input.files[0]) {
          var reader = new FileReader();

          reader.onload = function(e) {
            jQuery('.pro-icon').css("background-image", "url(" + e.target.result + ")");
          };

          reader.readAsDataURL(input.files[0]);
        }
      }
    </script>

    <script>
      // This example adds a search box to a map, using the Google Place Autocomplete
      // feature. People can enter geographical searches. The search box will return a
      // pick list containing a mix of places and predicted search terms.

      function initAutocomplete() {
        var componentForm = {
          street_number: 'short_name',
          route: 'long_name',
          locality: 'long_name',
          administrative_area_level_1: 'short_name',
          country: 'long_name',
          postal_code: 'short_name'
        };
        var map = new google.maps.Map(document.getElementById('map'), {
          center: {
            lat: <?php echo $setbuss->bus_latitude; ?>,
            lng: <?php echo $setbuss->bus_longitude; ?>
          },
          zoom: 13,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        });

        // Create the search box and link it to the UI element.
        var input = document.getElementById('pac-input');
        var eleLatLog = document.getElementById('latLog');
        var searchBox = new google.maps.places.SearchBox(input);
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

        // Bias the SearchBox results towards current map's viewport.
        map.addListener('bounds_changed', function() {
          searchBox.setBounds(map.getBounds());
        });

        var markers = [];
        // [START region_getplaces]
        // Listen for the event fired when the user selects a prediction and retrieve
        // more details for that place.
        searchBox.addListener('places_changed', function() {
          var places = searchBox.getPlaces();

          if (places.length == 0) {
            return;
          }

          // Clear out the old markers.
          markers.forEach(function(marker) {
            marker.setMap(null);
          });
          markers = [];

          // For each place, get the icon, name and location.
          var bounds = new google.maps.LatLngBounds();
          places.forEach(function(place) {
            eleLatLog.value = place.geometry.location;

            var icon = {
              url: place.icon,
              size: new google.maps.Size(71, 71),
              origin: new google.maps.Point(0, 0),
              anchor: new google.maps.Point(17, 34),
              scaledSize: new google.maps.Size(25, 25)
            };

            // Create a marker for each place.
            markers.push(new google.maps.Marker({
              map: map,
              icon: icon,
              title: place.name,
              position: place.geometry.location
            }));

            if (place.geometry.viewport) {
              // Only geocodes have viewport.
              bounds.union(place.geometry.viewport);
            } else {
              bounds.extend(place.geometry.location);
            }

            /* for (var component in componentForm) {
                 document.getElementById(component).value = '';
                 document.getElementById(component).disabled = false;
             }
             for (var i = 0; i < place.address_components.length; i++) {
                 var addressType = place.address_components[i].types[0];
                 if (componentForm[addressType]) {
                   var val = place.address_components[i][componentForm[addressType]];
                   document.getElementById(addressType).value = val;
                 }
               }
             */
          });
          map.fitBounds(bounds);
        });
        // [END region_getplaces]
      }
    </script>

    <script src="<?php echo base_url($this->config->item("theme_admin")); ?>/plugins/richtexteditor/rte.js"></script>
    <script src="<?php echo base_url($this->config->item("theme_admin")); ?>/plugins/richtexteditor/plugins/all_plugins.js"></script>
    <script>
      var editor1cfg = {}
      editor1cfg.toolbar = "mytoolbar";
      editor1cfg.toolbar_mytoolbar = "{bold,italic}|{fontsize}|{justifyleft,justifycenter,justifyright,justifyfull}|{insertorderedlist,insertunorderedlist,inserthorizontalrule}|removeformat" +
        "#{undo,redo,fullscreenenter,fullscreenexit}";
      var editor1 = new RichTextEditor("#editor1", editor1cfg);
      //{fontname}|{forecolor,backcolor}
    </script>

    <script src="<?php echo base_url() . "/" . $this->config->item('theme_admin'); ?>/plugins/chosen/chosen.jquery.min.js"></script>

    <script>
      $("#country_id").chosen();
      $("#city_id").chosen();
      $("#locality_id").chosen();
      $("#country_id").change(function() {
        $('#city_id').html("");
        var country_id = $(this).val();

        $.ajax({
            method: "POST",
            url: '<?php echo site_url("admin/city_json"); ?>',
            data: {
              country_id: country_id
            }
          })
          .done(function(data) {

            $.each(data, function(index, element) {
              $('#city_id').append("<option value='" + element.city_id + "'>" + element.city_name + "</option>");
            });
            $("#city_id").trigger("chosen:updated");
          });
      });
      $("#city_id").change(function() {
        $('#locality_id').html("");
        var city_id = $(this).val();

        $.ajax({
            method: "POST",
            url: '<?php echo site_url("admin/locality_json"); ?>',
            data: {
              city_id: city_id
            }
          })
          .done(function(data) {

            $.each(data, function(index, element) {
              $('#locality_id').append("<option value='" + element.locality_id + "'>" + element.locality + "</option>");
            });
            $("#locality_id").trigger("chosen:updated");
          });
      });
    </script>
    <script type="text/javascript">
      var config = {
        '.chosen-select': {},
        '.chosen-select-deselect': {
          allow_single_deselect: true
        },
        '.chosen-select-no-single': {
          disable_search_threshold: 10
        },
        '.chosen-select-no-results': {
          no_results_text: 'Oops, nothing found!'
        },
        '.chosen-select-width': {
          width: "95%"
        }
      }
      for (var selector in config) {
        $(selector).chosen(config[selector]);
      }
    </script>


</body>

</html>