@extends('layouts.main')

@section('content')

    <div >
        <h3> Rows </h3>
        <form action="{{route('import')}}" method="POST" enctype="multipart/form-data" class="mt-4 p-3">
            @csrf
            <div class="mb-3">
                <label for="formFile" class="form-label"> Upload excel file</label>
                <input class="form-control" type="file" name="file" id="formFile">
            </div>

            <button type="submit" class="btn btn-primary mt-3"> Upload </button>
        </form>

        @if($errors->any())
            @foreach ($errors->all() as $error)
                <div class="alert alert-danger mt-3"> {{ $error }} </div>
            @endforeach
        @endif

        @if(session()->has('success'))
            <div class="alert alert-success mt-4">
                {{ session()->get('success') }}
            </div>
        @endif

        <div class="accordion" id="accordion">
            @foreach($rows as $date => $dateRows)
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target=".row_{{$date}}" aria-expanded="true" aria-controls="collapseOne">
                            {{$date}}
                        </button>
                    </h2>

                    <div class="accordion-collapse collapse row_{{$date}}" data-bs-parent="#accordion">
                        <div class="accordion-body">
                            <table class="table">
                                <tbody>
                                @foreach($dateRows as $row)
                                <tr>
                                    <th scope="row">{{$row->id}}</th>
                                    <td>{{$row->name}}</td>
                                    <td>{{$row->date}}</td>
                                </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endforeach
            <div class="mt-4">
                {{ $rows->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>

@endsection
