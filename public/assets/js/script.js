function autocomplete(inp) {
    let currentFocus;
    
    inp.addEventListener("input", function(e) {
      let a, b, i, val = this.value;
      let arr = generatorArray(val);
      
      closeAllLists();
      if (!val) { return false;}
      currentFocus = -1;
      a = document.createElement("div");
      a.setAttribute("id", this.id + "autocomplete-list");
      a.setAttribute("class", "autocomplete-items");
      this.parentNode.appendChild(a);
      
      for (i = 0; i < arr.length; i++) {
        b = document.createElement("div");
        b.innerHTML = "<strong>" + arr[i] + "</strong>";
        b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
        b.addEventListener("click", function(e) {
            inp.value = this.getElementsByTagName("input")[0].value;
            closeAllLists();
          });
        a.appendChild(b);
      }
    });
  
    inp.addEventListener("keydown", function(e) {
        let x = document.getElementById(this.id + "autocomplete-list");
        if (x) x = x.getElementsByTagName("div");
        if (e.keyCode == 40) {
            currentFocus++;
            addActive(x);
        } else if (e.keyCode == 38) {
            currentFocus--;
            addActive(x);
        } else if (e.keyCode == 13) {
            e.preventDefault();
        if (currentFocus > -1) {
            if (x) x[currentFocus].click();
        }
      }
    });
    function addActive(x) {
        if (!x) return false;
        removeActive(x);
        if (currentFocus >= x.length) currentFocus = 0;
        if (currentFocus < 0) currentFocus = (x.length - 1);
        x[currentFocus].classList.add("autocomplete-active");
    }
    function removeActive(x) {
        for (let i = 0; i < x.length; i++) {
            x[i].classList.remove("autocomplete-active");
        }
    }
    function closeAllLists(elmnt) {
        var x = document.getElementsByClassName("autocomplete-items");
        for (let i = 0; i < x.length; i++) {
            if (elmnt != x[i] && elmnt != inp) {
                x[i].parentNode.removeChild(x[i]);
            }
        }
    }
    function generatorArray(val){
        let arr = [];
        let valClear = val.replace('%',' ').trim();
        if (valClear.length>2){
            arr.push('%'+valClear+'%');
            arr.push('%'+valClear);
            arr.push(valClear+'%');
                
            let valArr = valClear.split(" ");
            let valArrCount = valArr.length;
            if (valArrCount>1){
                let cloneValArr = [...valArr];
                for(let i=0; i<(valArrCount -1); i++){
                    
                    if (cloneValArr[i].trim().length>0){
                        cloneValArr[i] = cloneValArr[i].trim()+'%';
                        arr.push(cloneValArr.join(' ').replace('% ','%').trim());
                    }
                    cloneValArr[i] = valArr[i].trim();
                }
            }
        }
        return arr;
    }
    document.addEventListener("click", function (e) {
      closeAllLists(e.target);
    });
}

jQuery(function( $ ) {
   "use strict";
    
    let FireTableApp = {
        dataTable: null,
        dataTableEle: null,

        init: function () {
            this.cacheElements();
            this.bindEvents();
        },

        cacheElements: function() {
            this.dataTableEle = $('#table_properties');
        },

        bindEvents: function () {
            FireTableApp.bindEventsFilters();
            FireTableApp.initializeTable();
        },
        initializeTable: function () {
            FireTableApp.dataTable = FireTableApp.dataTableEle
                .on(
                    'preXhr.dt',
                    function ( e, settings, data ) {
                        data.filters = FireTableApp.getFilterData();
                    }
                )
                .DataTable({
                    processing: true,
                    serverSide: true,
                    autoWidth: true,
                    responsive: true,
                    pageLength: 10,
                    columns: [
                        { data:'fire_number'},
                        { data:'ignition_date'},
                        { data:'fire_out_date' },
                        { data:'fire_status' },
                        { data:'fire_cause' },
                        { data:'incident_name'},
                        { data:'geographic_description'},
                        { data:'location'},
                    ],
                    columnDefs: [{
                        targets: 'no-sort',
                        orderable: false,
                    }],
                    scrollCollapse: true,
                    ajax: {
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        url: baseUrl+'/api/v1/filter',
                        type: "POST"
                    },
                    initComplete: function (settings, json) {
                        FireTableApp.addStateReset(FireTableApp.dataTable);
                    },
                    drawCallback: function (settings) {
                        $('#modal-template').on('show.bs.modal', function (event) {
                            let modal = $(this);
                            let relatedTarget = event.relatedTarget;

                            modal.find('.modal-body').html($(relatedTarget).attr('modal-content'));
                        });

                        $('.location-popover').popover({
                            placement: 'bottom',
                            sanitize: false,
                            html: true
                        }).on('inserted.bs.popover', function (e) {
                            let image = $(e.target).data('map') ?? null;

                            if (image !== null) {
                                $('.pop-map').html(image);
                            }
                        });
                    },
                });
        },
        bindEventsFilters: function () {
            $('.on-filters').on('click', '#apply-filters, #clear-all-filters', function () {
                let valFireCause = $('#select_fire_cause').val();
                let valFireStatus = $('#select_fire_status').val();
                let valDesc = $('#filter_geo_desc').val()
                if ($(this).attr('id')=='clear-all-filters'){
                    $('#filter_geo_desc').val('');
                    $('#select_fire_cause').val('');
                    $('#select_fire_status').val('');
                    $('#operation_fire_cause').val('Equal');
                    $('#operation_fire_status').val('Equal');
                    $('#condition_filters').val('AND');
                    
                    if (valFireCause.length > 0 || valFireStatus.length > 0 || valDesc.length>0) {
                        FireTableApp.dataTable.draw();
                    }
                    
                } else if ($(this).attr('id')=='apply-filters'){
                    
                    if (valFireCause.length > 0 || valFireStatus.length > 0 || valDesc.length>0) {
                        FireTableApp.dataTable.draw();
                    }
                }
                FireTableApp.saveStorageFilters();
            });
        },
        getFilterData: function () {
            let filters = {};

            let valFireCause = $('#select_fire_cause').val();
            let valFireStatus = $('#select_fire_status').val();
            let valDesc = $('#filter_geo_desc').val();

            if (valFireCause.length > 0) {
                filters.fire_cause = {
                    value: valFireCause,
                    operation: $('#operation_fire_cause').val(),
                };
            }

            if (valFireStatus.length > 0) {
                filters.fire_status = {
                    value: valFireStatus,
                    operation: $('#operation_fire_status').val(),
                };
            }
            
            if (valDesc.length > 2) {
                filters.geo_desc = valDesc;
            }
            
            if (valFireCause.length > 0 || valFireStatus.length > 0 || valDesc.length>1){
                filters.condition_filters = $('#condition_filters').val();
            }
            
            return filters;
        },
        addStateReset: function (dataTable) {
            
            
            const downloadButton = document.createElement('a');
            downloadButton.href = baseUrl+'/export/all';
            downloadButton.innerHTML = 'Download All';
            downloadButton.id = `${dataTable.table().node().id}-download-all`;
            downloadButton.download = 'fire-properties.csv';
            downloadButton.className = 'btn btn-sm btn-secondary me-1';
            $(dataTable.table().container()).find('.dataTables_filter').empty().prepend(downloadButton);
            
            const dFilterButton = document.createElement('button');
            dFilterButton.innerHTML = 'Download By Filter';
            dFilterButton.id = `${dataTable.table().node().id}-download-filter`;
            //dFilterButton.download = 'fire-properties.csv';
            dFilterButton.className = 'btn btn-sm btn-warning me-1';
            $(dataTable.table().container()).find('.dataTables_filter').prepend(dFilterButton);
            dFilterButton.addEventListener("click", function () {
                let filter = JSON.stringify(FireTableApp.getFilterData());
                const link = document.createElement('a');
                link.setAttribute('download', 'fire-properties.csv');
                link.setAttribute('href',  baseUrl+'/export/filter?df='+Base64.encode(filter, true));
                link.click()
            });
            
        },
        saveStorageFilters: function() {
            let filters = FireTableApp.getFilterData();
            localStorage.setItem('data_filters', JSON.stringify(filters));
        },
    };
    
    FireTableApp.init();
    autocomplete(document.getElementById("filter_geo_desc"));
});

