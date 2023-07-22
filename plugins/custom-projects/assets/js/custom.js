
  jQuery(document).ready(function() {

    function getProjects() {
      $.ajax({
        url: custom_projects_ajax_object.ajax_url,
        type: 'POST',
        data: {
          action: 'custom_projects',
        },
        dataType: 'json',
        success: function(response) {
          if (response.success) {

            console.log(response.data);
          } else {
       
            console.log('No projects found.');
          }
        },
        error: function(errorThrown) {
          console.log('Error:', errorThrown);
        },
      });
    }

    getProjects();
  });
