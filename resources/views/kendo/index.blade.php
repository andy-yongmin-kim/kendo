<!-- <!DOCTYPE html>
<html>
<head>
    <title></title>
    <link rel="stylesheet" href="styles/kendo.common.min.css" />
    <link rel="stylesheet" href="styles/kendo.default.min.css" />
    <link rel="stylesheet" href="styles/kendo.default.mobile.min.css" />

    <script src="js/jquery.min.js"></script>
    
    
    <script src="js/kendo.all.min.js"></script>
    
    

</head>
<body>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jszip/2.4.0/jszip.min.js"></script>

<div id="example">
    <div id="grid"></div>
    <script>
        $(document).ready(function () {
            var crudServiceBaseUrl = "https://demos.telerik.com/kendo-ui/service",
                dataSource = new kendo.data.DataSource({
                    transport: {
                        read: {
                            url: crudServiceBaseUrl + "/detailproducts",
                            dataType: "jsonp"
                        },
                        update: {
                            url: crudServiceBaseUrl + "/detailproducts/Update",
                            dataType: "jsonp"
                        },
                        destroy: {
                            url: crudServiceBaseUrl + "/detailproducts/Destroy",
                            dataType: "jsonp"
                        },
                        parameterMap: function (options, operation) {
                            if (operation !== "read" && options.models) {
                                return { models: kendo.stringify(options.models) };
                            }
                        }
                    },
                    batch: true,
                    pageSize: 20,
                    autoSync: true,
                    aggregate: [{
                        field: "TotalSales",
                        aggregate: "sum"
                    }],
                    group: {
                        field: "Category.CategoryName",
                        dir: "desc",
                        aggregates: [
                            { field: "TotalSales", aggregate: "sum" }
                        ]
                    },
                    schema: {
                        model: {
                            id: "ProductID",
                            fields: {
                                ProductID: { editable: false, nullable: true },
                                Discontinued: { type: "boolean", editable: false },
                                TotalSales: { type: "number", editable: false },
                                TargetSales: { type: "number", editable: false },
                                LastSupply: { type: "date" },
                                UnitPrice: { type: "number" },
                                UnitsInStock: { type: "number" },
                                Category: {
                                    defaultValue: {
                                        CategoryID: 8,
                                        CategoryName: "Seafood"
                                    }
                                },
                                Country: {
                                    defaultValue: {
                                        CountryNameLong: "Bulgaria",
                                        CountryNameShort: "bg"
                                    }
                                }
                            }
                        }
                    }
                });

            $("#grid").kendoGrid({
                dataSource: dataSource,
                columnMenu: {
                    filterable: false
                },
                height: 680,
                editable: "incell",
                pageable: true,
                sortable: true,
                navigatable: true,
                resizable: true,
                reorderable: true,
                groupable: true,
                filterable: true,
                dataBound: onDataBound,
                toolbar: ["search", "excel", "pdf"],
                columns: [{
                    selectable: true,
                    width: 75,
                    attributes: {
                        "class": "checkbox-align",
                    },
                    headerAttributes: {
                        "class": "checkbox-align",
                    }
                }, {
                    field: "ProductName",
                    title: "Product Name",
                    template: "<div class='product-photo' style='background-image: url(../content/web/foods/#:data.ProductID#.jpg);'></div><div class='product-name'>#: ProductName #</div>",
                    width: 300
                }, {
                    field: "UnitPrice",
                    title: "Price",
                    format: "{0:c}",
                    width: 105
                }, {
                    field: "Discontinued",
                    title: "In Stock",
                    template: "<span id='badge_#=ProductID#' class='badgeTemplate'></span>",
                    width: 130,
                }, {
                    field: "Category.CategoryName",
                    title: "Category",
                    editor: clientCategoryEditor,
                    groupHeaderTemplate: "Category: #=data.value#, Total Sales: #=kendo.format('{0:c}', aggregates.TotalSales.sum)#",
                    width: 125
                }, {
                    field: "CustomerRating",
                    title: "Rating",
                    template: "<input id='rating_#=ProductID#' data-bind='value: CustomerRating' class='rating'/>",
                    editable: returnFalse,
                    width: 140
                }, {
                    field: "Country.CountryNameLong",
                    title: "Country",
                    template: "<div class='k-text-center'><img src='../content/web/country-flags/#:data.Country.CountryNameShort#.png' alt='#: data.Country.CountryNameLong#' title='#: data.Country.CountryNameLong#' width='30' /></div>",
                    editor: clientCountryEditor,
                    width: 120
                }, {
                    field: "UnitsInStock",
                    title: "Units",
                    width: 105
                }, {
                    field: "TotalSales",
                    title: "Total Sales",
                    format: "{0:c}",
                    width: 140,
                    aggregates: ["sum"],
                }, {
                    field: "TargetSales",
                    title: "Target Sales",
                    format: "{0:c}",
                    template: "<span id='chart_#= ProductID#' class='sparkline-chart'></span>",
                    width: 220
                },
                { command: "destroy", title: "&nbsp;", width: 120 }],
            });
        });

        function onDataBound(e) {
            var grid = this;
            grid.table.find("tr").each(function () {
                var dataItem = grid.dataItem(this);
                var themeColor = dataItem.Discontinued ? 'success' : 'error';
                var text = dataItem.Discontinued ? 'available' : 'not available';

                $(this).find(".badgeTemplate").kendoBadge({
                    themeColor: themeColor,
                    text: text,
                });

                $(this).find(".rating").kendoRating({
                    min: 1,
                    max: 5,
                    label: false,
                    selection: "continuous"
                });

                $(this).find(".sparkline-chart").kendoSparkline({
                    legend: {
                        visible: false
                    },
                    data: [dataItem.TargetSales],
                    type: "bar",
                    chartArea: {
                        margin: 0,
                        width: 180,
                        background: "transparent"
                    },
                    seriesDefaults: {
                        labels: {
                            visible: true,
                            format: '{0}%',
                            background: 'none'
                        }
                    },
                    categoryAxis: {
                        majorGridLines: {
                            visible: false
                        },
                        majorTicks: {
                            visible: false
                        }
                    },
                    valueAxis: {
                        type: "numeric",
                        min: 0,
                        max: 130,
                        visible: false,
                        labels: {
                            visible: false
                        },
                        minorTicks: { visible: false },
                        majorGridLines: { visible: false }
                    },
                    tooltip: {
                        visible: false
                    }
                });

                kendo.bind($(this), dataItem);
            });
        }

        function returnFalse() {
            return false;
        }

        function clientCategoryEditor(container, options) {
            $('<input required name="Category">')
                .appendTo(container)
                .kendoDropDownList({
                    autoBind: false,
                    dataTextField: "CategoryName",
                    dataValueField: "CategoryID",
                    dataSource: {
                        data: categories
                    }
                });
        }

        function clientCountryEditor(container, options) {
            $('<input required name="Country">')
                .appendTo(container)
                .kendoDropDownList({
                    dataTextField: "CountryNameLong",
                    dataValueField: "CountryNameShort",
                    template: "<div class='dropdown-country-wrap'><img src='../content/web/country-flags/#:CountryNameShort#.png' alt='#: CountryNameLong#' title='#: CountryNameLong#' width='30' /><span>#:CountryNameLong #</span></div>",
                    dataSource: {
                        transport: {
                            read: {
                                url: " https://demos.telerik.com/kendo-ui/service/countries",
                                dataType: "jsonp"
                            }
                        }
                    },
                    autoWidth: true
                });
        }

        var categories = [{
            "CategoryID": 1,
            "CategoryName": "Beverages"
        }, {
            "CategoryID": 2,
            "CategoryName": "Condiments"
        }, {
            "CategoryID": 3,
            "CategoryName": "Confections"
        }, {
            "CategoryID": 4,
            "CategoryName": "Dairy Products"
        }, {
            "CategoryID": 5,
            "CategoryName": "Grains/Cereals"
        }, {
            "CategoryID": 6,
            "CategoryName": "Meat/Poultry"
        }, {
            "CategoryID": 7,
            "CategoryName": "Produce"
        }, {
            "CategoryID": 8,
            "CategoryName": "Yongmin"
        }];
    </script>

    <style type="text/css">
        .customer-photo {
            display: inline-block;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-size: 32px 35px;
            background-position: center center;
            vertical-align: middle;
            line-height: 32px;
            box-shadow: inset 0 0 1px #999, inset 0 0 10px rgba(0,0,0,.2);
            margin-left: 5px;
        }

        .customer-name {
            display: inline-block;
            vertical-align: middle;
            line-height: 32px;
            padding-left: 3px;
        }

        .k-grid tr .checkbox-align {
            text-align: center;
            vertical-align: middle;
        }

        .product-photo {
            display: inline-block;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-size: 32px 35px;
            background-position: center center;
            vertical-align: middle;
            line-height: 32px;
            box-shadow: inset 0 0 1px #999, inset 0 0 10px rgba(0,0,0,.2);
            margin-right: 5px;
        }

        .product-name {
            display: inline-block;
            vertical-align: middle;
            line-height: 32px;
            padding-left: 3px;
        }

        .k-rating-container .k-rating-item {
            padding: 4px 0;
        }

        .k-rating-container .k-rating-item .k-icon {
            font-size: 16px;
        }

        .dropdown-country-wrap {
            display: flex;
            flex-wrap: nowrap;
            align-items: center;
            white-space: nowrap;
        }

        .dropdown-country-wrap img {
            margin-right: 10px;
        }

        #grid .k-grid-edit-row > td > .k-rating {
            margin-left: 0;
            width: 100%;
        }
    </style>
</div>


    

</body>
</html> -->

<!DOCTYPE html>
<html>
<head>
    <title></title>
    <link rel="stylesheet" href="styles/kendo.common.min.css" />
    <link rel="stylesheet" href="styles/kendo.default.min.css" />
    <link rel="stylesheet" href="styles/kendo.default.mobile.min.css" />

    <script src="js/jquery.min.js"></script>
    
    
    <script src="js/kendo.all.min.js"></script>
    
    

</head>
<body>
    <div id="example">
    <div id="grid"></div>
    <script>

        function onChange(arg) {
            console.log("The selected product ids are: [" + this.selectedKeyNames().join(", ") + "]");
        }

        $(document).ready(function() {
            
            $("#grid").kendoGrid({
                toolbar: ["Delete"],
                dataSource: {
                    type: "json",
                    transport: {
                        read: "/data-details",
                        destroy: {
                            url:  "/order/destory",
                            dataType: "json"
                        },
                    },
                    schema: {
                        model: {
                            fields: {
                                
                                id: { type: "number" },
                                customer_seq: { type: "number" },
                                customer_name: { type: "string" },
                                type: { type: "string" },
                                date: { type: "date" },
                                delivery_date: { type: "date" },
                                express_name: { type: "string" },
                                invoice_num: { type: "number" },
                                pack_num: { type: "number" },
                                invoice_to: { type: "string" },
                                order_num: { type: "number" },
                                order_detail:{type:""},
                                order_quantity: { type: "number" },
                                
                            }
                        }
                    },
                    pageSize: 20,
                    serverPaging: false,
                    serverFiltering: true,
                    serverSorting: true
                },
                height: 550,
                filterable: true,
                sortable: true,
                pageable: true,
                persistSelection: true,
                change: onChange,
                columns: [
                    { selectable: true, width: "50px" },
                    
                    {   field:"id",
                        template: "<div class='order-id' data-id='#=id#'>#: id #</div>",
                        dataSource: [ { id: this.id }]

                    },{
                        field:"customer_seq",
                    }, {
                        field:"customer_name",
                    }, {
                        field: "type",
                        title: "Type"
                    }, {
                        field: "date",
                        title: "Date",
                        format: "{0:MM/dd/yyyy}"
                    },{
                        field: "delivery_date",
                        format: "{0:MM/dd/yyyy}"
                    },{
                        field: "express_name",
                        title: "express_name",
                        
                    },{
                        field: "invoice_num",
                        title: "invoice_num",
                        
                    },{
                        field: "pack_num",
                        title: "pack_num",
                        
                    },{
                        field: "invoice_to",
                        title: "invoice_to",
                        
                    },{
                        field: "order_num",
                        title: "order_num",
                        
                    },{
                        field: "order_detail",
                        title: "order_detail",
                        
                    },{
                        field: "order_quantity",
                        title: "order_quantity",
                        
                    },
                    { command: [ "edit", "destroy" ] }

                ],
                editable: true
            });
            
            
        });




        // $("#grid").on("click", ".order-id", function() {
        //     var $tr = $(this).closest("tr"),
        //     grid = $("#grid").data("kendoGrid");

        //     grid.removeRow($tr);

        //     var id = $(this).closest('.order-id');
        //     console.log("this is id");
        //     console.log(id);
        //     //ajax ..
        //     // $.ajax (
        //     //     {
        //     //         url:"/order/destory" + id,
        //     //         type: 'DELETE',
        //     //         data: {
        //     //             "id": id
        //     //        }
        //     //     }
               
        //     // )
        // });


        function whenYourDeleteButtonIsClicked(){
          var grid = $("#grid").data("test_yongmin");
          $("#grid").find("input:checked").each(function(){
            grid.removeRow($(this).closest('tr'));
          })
        }
    </script>
</div>


    

</body>
</html>
