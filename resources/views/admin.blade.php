@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-xl">
            <div class="card mt-3">
                <div class="card-header">{{ __('Parsing control') }}</div>
                <div class="card-body">
                    <p><b>Status: </b>
                        @if($status)
                            <span class="badge badge-success">Включен</span>
                            @else
                            <span class="badge badge-danger">Выключен</span></p>
                        @endif
                    <p><b>Управление: </b></p>
                    <div class="d-flex flex-row">
                    @if($status)
                        <form action="{{ url('/parser/stop') }}" method="post">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                            <button class="btn btn-danger" type="submit">Остановить</button>
                        </form>
                        <form action="{{ url('/parser/restart') }}" method="post">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                            <button class="btn btn-secondary" type="submit">Перезапустить</button>
                        </form>
                    @else
                        <form action="{{ url('/parser/start') }}" method="post">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                            <button class="btn btn-success" type="submit">Запустить</button>
                        </form>
                    @endif
                    </div>


                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header d-flex justify-content-between">{{ __('Parsing logs') }}<button class="btn btn-primary" onclick="location.reload()"><span class="glyphicon glyphicon-refresh"></span> Refresh</button></div>
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Date</th>
                        <th scope="col">Request Method</th>
                        <th scope="col">Request URL</th>
                        <th scope="col">Response HTTP Code</th>
                        <th scope="col">Response Body</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($logs as $log)
                    <tr class="mw-100">
                        <th scope="row">{{ $log['id'] }}</th>
                        <td class="text-nowrap">{{ $log['date'] }}</td>
                        <td>{{ $log['request_method'] }}</td>
                        <td class="overflow-auto">{{ $log['request_url'] }}</td>
                        <td>{{ $log['response_http_code'] }}</td>
                        <td class="text-truncate"><textarea rows="1">{{ $log['response_body'] }}</textarea></td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    {{ $logs->Links("pagination::bootstrap-4") }}
</div>

@endsection
