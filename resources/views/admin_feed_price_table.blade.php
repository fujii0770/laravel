@extends('crudbooster::admin_template_with_left_sidebar')

@section('content')

    @push('bottom')
        <script type="text/javascript">
            $(document).ready(function () {
                var $window = $(window);
                function checkWidth() {
                    var windowsize = $window.width();
                    if (windowsize > 500) {
                        console.log(windowsize);
                        $('#box-body-table').removeClass('table-responsive');
                    } else {
                        console.log(windowsize);
                        $('#box-body-table').addClass('table-responsive');
                    }
                }
                checkWidth();
                $(window).resize(checkWidth);
                var elements = document.getElementsByClassName('column-title');
                var firstElement = elements[0];
                $(firstElement).focus();
            });
        </script>
    @endpush
    @push('head')
        <style>
            .column-title {
                width: 100%;
                border: none;
                background-color: unset;
            }
            .box-action{
                display: flex;
                justify-content: space-between;
            }
            .panel-heading {
                padding-left: 0;
                background-color: white;
                color: #9bbce0;
                border-color: white;
            }
            #table_dashboard tr td:first-child{
                width: 50%;
                word-break: break-all;
            }
        </style>
    @endpush
    <div class="box">
        <div class="panel-heading">
            <strong> {{trans('ebi.餌、薬単価設定')}} </strong>
            <hr class="bottom-hr w-100">
        </div>
        <div class="box-body no-padding">
            <form id='form-table' method="post" action="{{ $postUrl }}">
                <input type='hidden' name='button_name' value=''/>
                <input type='hidden' name='_token' value='{{csrf_token()}}'/>
                <table id='table_dashboard' class="table table-hover table-striped table-bordered">
                    <thead>
                        <tr class="active">
                            <?php
                            foreach ($columns as $col) {
                                if ($col['visible'] === FALSE) continue;

                                $sort_column = Request::get('filter_column');
                                $colname = $col['label'];
                                $name = $col['name'];
                                $field = $col['field_with'];
                                $width = ($col['width']) ?: "auto";
                                $mainpath = trim(CRUDBooster::mainpath(), '/').$build_query;
                                echo "<th width='$width'>".$colname. "</th>";
                            }
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($result)==0)
                            <tr class='warning table-data-not-found'>
                                <?php if($button_bulk_action && $show_numbering):?>
                                <td colspan='{{count($columns)+3}}' align="center">
                                <?php elseif( ($button_bulk_action && ! $show_numbering) || (! $button_bulk_action && $show_numbering) ):?>
                                <td colspan='{{count($columns)+2}}' align="center">
                                <?php else:?>
                                <td colspan='{{count($columns)+1}}' align="center">
                                    <?php endif;?>

                                    <i class='fa fa-search'></i> {{trans("crudbooster.table_data_not_found")}}
                                </td>
                            </tr>
                        @endif

                        @foreach($baitInventoriesList as $list)
                            @foreach($html_contents['data'] as $index => $row)
                                @php
                                    $inputOldName = "feed_price[price][{{ $index }}]";
                                @endphp
                                @if($row->ebi_bait_inventories_id == $list->id)
                                    <tr>
                                        <td>{{ $list->bait_name }}</td>
                                        <td><input class="column-title" name="feed_price[price][{{ $list->id }}]" type="number" min="1" max="2147483647" required value= "{{ old($inputOldName, $row->price) }}"></td>
                                        <td>{{ $list->amount_per_bag }}</td>
                                    </tr>
                                @endif
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
                <div class="box-action">
                    <div>
                    </div>

                    <div>
                        <button class="btn btn-primary btn-submit" type="submit">{{trans("ebi.登録")}}</button>
                    </div>
                </div>
            </form><!--END FORM TABLE-->
        </div>
    </div>
@endsection
