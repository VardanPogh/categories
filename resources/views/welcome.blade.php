<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Categories</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
          integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.4.0.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="//ajax.aspnetcdn.com/ajax/jquery.ui/1.10.3/jquery-ui.min.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

    <!-- Styles -->
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Nunito', sans-serif;
            font-weight: 200;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .links > a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .li_sub {
            cursor: pointer;
        }

        .emptySpace {
            border: medium dotted red;
            height: 36px;
            margin: 4px;
            width: 200px;
        }

        li {
            list-style-type: none;
            padding-left: 20px;
            width: 200px;
            background: #badbc1;
            border: 1px solid #0b4f15;
        }

        ul {
            padding-left: 20px;
            width: 300px;
            background: #d8db71;
            border: 1px solid #4f4a12;
            padding-bottom: 10px;
        }

    </style>
</head>
<body>
<h1>Categories</h1>
{{--Language section--}}
<div class="d-flex offset-1">
    <div class="form-check col-1">
        <input class="form-check-input" type="radio" name="radio" id="radio1" value="name" checked>
        <label class="form-check-label" for="radio1">
            Arm
        </label>
    </div>
    <div class="form-check col-1">
        <input class="form-check-input" type="radio" name="radio" id="radio2" value="eng_name">
        <label class="form-check-label" for="radio2">
            Eng
        </label>
    </div>
    <div class="form-check col-1">
        <input class="form-check-input" type="radio" name="radio" id="radio3" value="rus_name">
        <label class="form-check-label" for="radio3">
            Rus
        </label>
    </div>
</div>
{{-- END Language section--}}

<div class="flex-center position-ref full-height">
    <div class="col-12 d-flex">
        <div class="col-6">
            <h2>Add new</h2>
            {{--Add new Categories--}}

            <label>Armenian</label>
            <input class="input-group-text" name="category_name" id="add_input">
            <label>Russian</label>
            <input class="input-group-text" name="category_name_eng" id="add_input_eng">
            <label>English</label>
            <input class="input-group-text" name="category_name_rus" id="add_input_rus">
            <br>
            <select id="add_select" class="custom-select col-4">
                <option value="">Select</option>
                @foreach ($categories as $cat)
                    <option value="{{$cat->id}}">{{$cat->name}}</option>
                @endforeach
            </select>
            {{--END Add new Categories--}}

            <br>
            <button type="button" class="btn btn-success col-3" id="add_button">ADD</button>
        </div>
        {{--Categories--}}

        <div class="col-6" id="ul_content">
            @foreach ($categories as $cat)
                <ul class="ul_cat" id="cat_{{ $cat->id }}">{{$cat->name}}
                    @if($cat->children != '[]')
                        @foreach ($cat->children as $sub)
                            <li class="li_sub" id="sub_{{ $sub->id }}">{{ $sub->name}}</li>
                        @endforeach
                    @endif
                </ul>
            @endforeach
        </div>
        {{--END Categories--}}


    </div>
</div>
</body>
<script>
    $(document).ready(function () {
        setSortable();
    });

    //Add new categories
    $("#add_button").click(function () {
        const catName = $('#add_input').val();
        const catNameEng = $('#add_input_eng').val();
        const catNameRus = $('#add_input_rus').val();
        const parentCat = $('#add_select').val();
        $.ajax({
            url: "/",
            type: "POST",
            data: {
                "_token": "{{ csrf_token() }}",
                "name": catName,
                "name_eng": catNameEng,
                "name_rus": catNameRus,
                "parent_id": parentCat,
            },
            success: function (response) { //visualisation of the new category
                const newId = response.id;
                if (parentCat == '') {
                    $('#ul_content').append('<ul class="ul_cat ui-sortable" id="cat_' + newId + '">' + catName + '</ul>')
                    $('#add_select').append('<option value="' + newId + '">' + catName + '</option>');
                    setSortable();
                } else {
                    $('#cat_' + parentCat).append('<li class="li_sub" id="sub_' + newId + '">' + catName + '</li>')
                }
            },
            error: function (err) {
                alert(err.responseJSON.message);
            }
        });
    });

    function setSortable() {
        $('.ul_cat').sortable({
            placeholder: 'emptySpace',
            connectWith: '.ul_cat',
            receive: function (event, ui) {
                const updated_sub_id = ui.item[0].id.split('_')[1];
                const updated_cat_id = this.id.split('_')[1];
                $.ajax({ //update category position
                    url: "",
                    type: "PUT",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "id": updated_sub_id,
                        "parent_id": updated_cat_id,
                    },
                });
            }
        });
    }

    //Change language
    $('input[type=radio][name=radio]').change(function () {
        $.ajax({
            url: "/lang",
            type: "PUT",
            data: {
                "_token": "{{ csrf_token() }}",
                "name": this.value,
            },
            success: function (response) {
                $('#ul_content').html('');
                jQuery.each(response, function (i, val) {
                    $('#ul_content').append('<ul class="ul_cat" id="cat_' + val.id + '">' + val.name + '');
                    if (val.children != '[]') {
                        jQuery.each(val.children, function (ind, sub) {
                            $('#cat_' + val.id).append('<li class="li_sub" id="sub_' + sub.id + '">' + sub.name + '</li>');
                        })
                    }
                    $('#ul_content').append('</ul>')
                });
                setSortable();
            },
        });
    });

</script>
</html>
