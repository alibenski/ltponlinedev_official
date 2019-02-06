  $(document).ready(function(){
      $(".wx").select2({
        //theme: "bootstrap",   
        minimumResultsForSearch: -1,
        placeholder: 'Select Course Here',
        "language": {
            "noResults": function(){
                return "<strong class='text-danger'>Sorry no schedule offered for this course this term. </strong><br> <a href='https://learning.unog.ch/language-index' target='_blank' class='btn btn-info'>click here to see the available courses and their schedules</a>";
                }
        },
        escapeMarkup: function (markup) {
        return markup;
        }
      });
  }); 

  $(document).ready(function(){
      $(".select2-multi").select2({
        //theme: "bootstrap",
        allowClear: true,
        minimumResultsForSearch: -1,
        maximumSelectionLength: 2,
        width: 'resolve', // need to override the changed default
        closeOnSelect: false,
        templateResult: formatResult,
        //templateSelection: formatResult, 
        placeholder: 'Choose Here',
        "language": {
            "noResults": function(){
                return "<strong class='text-danger'>Sorry no schedule offered for this course this term. </strong><br> <a href='https://learning.unog.ch/language-index' target='_blank' class='btn btn-info'>click here to see the available courses and their schedules</a>";
                }
        },
        escapeMarkup: function (markup) {
        return markup;
        }
      }); 
            function formatResult (schedule) {
        if (!schedule.id) { return schedule.text; }
        
        var $schedule = $(
          '<i class="fa fa-plus-circle" aria-hidden="true"></i><span style="font-style:inherit; margin-left:10px;">'  + schedule.text + '</span>'
        );
        return $schedule;
      };
      // arrange in order of of being selected
      $(".select2-multi").on("select2:select", function (evt) {
        var element = evt.params.data.element;
        var $element = $(element);

        $element.detach();
        $(this).append($element);
        $(this).trigger("change");
      });

      $('.multi-clear').click(function() {
        $(".select2-multi").val(null).trigger("change"); 
        $("#first").empty();
        $("#second").empty();
      });
      // close dropdown list after 2 selections to override "closeOnSelect: false" param 
      $('.select2-multi').change(function(){
        var ele = $(this);
        if(ele.val()==null)
        {
          ele.select2('open');
        }
        else if(ele.val().length==2)
        {
          ele.select2('close');
        }
      });

  });

  $(document).ready(function(){
      // multi values, with last selected
      var old_values = [];
      var test = $(".select2-multi");

      test.on("select2:select", function(event) {
        var values = [];
        var values_index = [];
        var values_id = []; 
        //event.params.data.id; 
        // copy all option values from selected
        $(event.currentTarget).find("option:selected").each(function(i, selected){          
          values[i] = $(selected).text();
          values_index[i] = i;
          values_id[i] = $(selected).val();
        });

        var first =  values[0];
        var second =  values[1];
        var first_index =  values_index[0];
        var second_index =  values_index[1];
        var first_id =  values_id[0];
        var second_id =  values_id[1];

        if(first != null) {
          $("#first").text(first).css("color","green").attr("name",first_index).attr("data-id",first_id);
        }
        if(second != null) {
          $("#second").text(second).css("color","#337ab7").attr("name",second_index).attr("data-id",second_id);
        } else {
          $("#second").text("none");
        }
        // doing a diff of old_values gives the new values selected
        var last = $(values).not(old_values).get();
        // update old_values for future use
        old_values = values;
        // output values (all current values selected)
        //console.log("selected values: ", values);
        // output last added value
        //console.log("last added: ", last);
        });

      test.on("select2:unselect", function(e){
        //console.log(e);        console.log(e.params);         console.log(e.params.data);        
        var values_id = e.params.data.id;
        
        var elem_una = document.getElementById("first");
        var get_id_una = elem_una.getAttribute("data-id");

        var elem_dos = document.getElementById("second");
        var get_id_dos = elem_dos.getAttribute("data-id");
        var get_text_dos = elem_dos.innerHTML;
        var get_index_dos = elem_dos.getAttribute("name");

        if(values_id == get_id_una){
          //$("#first").removeAttr("value");
          $("#first").text(get_text_dos).attr("name",get_index_dos);
          $("#second").empty();
        } else if (values_id == get_id_dos){
          $("#second").empty();
          $( 'p[name="1"]' ).empty();
        } 
        });
  });