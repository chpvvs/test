@extends('admin::layouts.wrapper')

@section('page')
    <div class="content">
        <div class="content__inside">
            <div class="cabinet">
                <div class="cabinet__header">
                    <div class="cabinet__title">
                        {{ $page_title }}
                    </div>
                </div>

                <table class='table table-condenced table-striped'>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Дата регистрации</th>
                            <th>Фамилия</th>
                            <th>Имя</th>
                            <th>Отчество</th>
                            <th>Телефон</th>
                            <th>Email</th>
                            <th>Город</th>
                            <th>Адрес</th>
                            <th>Индекс</th>
                            <th>Заказы</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->created_at->format('H:i d.m.Y') }}</td>
                            <td>{{ $user->last_name }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->father_name }}</td>
                            <td>{{ $user->phone }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->city }}</td>
                            <td>{{ $user->address }}</td>
                            <td>{{ $user->index }}</td>
                            <td>
                                @if ($user->orders->count() > 0)
                                    <a href="#" class='orders_details' data-id="{{ $user->id }}">{{ $user->orders->count() }} Подробнее...</a>
                                @else
                                    {{ $user->orders->count() }}
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @foreach ($users as $user)
                    <div class="orders_details_container" data-id="{{ $user->id }}">
                        @foreach ($user->orders as $data)
                            @if ($data->goods->count())
                                <h3>Заказ № {{ $data->id }} от {{ $data->created_at->format('H:i d.m.Y') }}</h3>
                                <p>@lang('busshop::main.status') <span>{{ Config::get('busshop.order_status.'.$data->order_status.'.text') }}</span></p>
                                <p>@lang('busshop::main.payment_status') <span>{{ Config::get('busshop.payment_status.'.$data->payment_status.'.text') }}</span></p>
                                <p>@lang('busshop::main.wishes') <span>{{ $data->order_comment }}</span></p>
                                <p>@lang('busshop::main.payment_type') <span>{{ Config::get('busshop.payments.'.$data->payment) }}</span></p>
                                <p>@lang('busshop::main.delivery') <span>{{ Config::get('busshop.deliveries.'.$data->delivery) }}</span></p>
                                @if ($data->delivery == 'bus')
                                    <p>@lang('busshop::main.delivery_address') <span>{{ $data->entity_address }}</span></p>
                                @endif
                                @if ($data->delivery == 'delivery')
                                    <p>@lang('busshop::main.delivery_address') <span>{{ $data->entity_address }}</span></p>
                                @endif
                                <table class="table table-condensed table-striped">
                                    <thead>
                                        <tr>
                                            <th>@lang('busshop::main.good_name')</th>
                                            <th>@lang('busshop::main.article')</th>
                                            <th>@lang('busshop::main.quantity')</th>
                                            <th>@lang('busshop::main.good_price')</th>
                                            <th>@lang('busshop::main.curr_price')</th>
                                            <th>@lang('busshop::main.remains')</th>
                                            <th>@lang('busshop::main.total')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($data->goods as $good)
                                        <tr>
                                            <td>
                                                {{ $good->lang->name }}
                                            </td>
                                            <td>
                                                {{ $good->article }}
                                            </td>
                                            <td>
                                                {{ $good->pivot->quantity }}
                                            </td>
                                            <td>
                                                {{ $good->pivot->good_price }}
                                            </td>
                                            <td>
                                                {{ (isset($good->price[0])) ? $good->price[0]->c_value : '' }}
                                            </td>
                                            <td>
                                                {{ $good->remains }}
                                            </td>
                                            <td>
                                                {{ $good->pivot->good_price * $good->pivot->quantity }}
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <p>@lang('busshop::main.sum') {{ $data->total_price }}</p>
                            @endif
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    </div>

@stop


@section('links')
<style type="text/css">    
    .orders_details_container { display: none; }
</style>
@stop

@section('scripts')
    <script>
        $(document).ready(function () {
            $('.orders_details').click(function(e) {
                e.stopPropagation();e.preventDefault();
                var user_id = $(this).attr('data-id');
                $('.orders_details_container').slideUp(400);
                $('.orders_details_container[data-id="'+user_id+'"]').slideDown(400);                
            });
        });
    </script>
@stop
