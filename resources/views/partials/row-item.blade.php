@extends('layouts.main')

@section('content')

    <div >
        <h3> Import page </h3>
        <form action="{{route('import')}}" method="POST" enctype="multipart/form-data" class="mt-4 p-3">
            @csrf
            <div class="mb-3">
                <label for="formFile" class="form-label">Default file input example</label>
                <input class="form-control" type="file" name="file" id="formFile">
            </div>

            <button type="submit" class="btn btn-primary mt-3"> Upload </button>
        </form>

        @if($errors->any())
            {{ implode('', $errors->all('<div>:message</div>')) }}
        @endif

        @if(session()->has('success'))
            <div class="alert alert-success mt-4">
                {{ session()->get('success') }}
            </div>
        @endif


        <div class="accordion" id="accordionExample">
            @foreach($rows as $k => $dateRows)
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target=".row_{{$k}}" aria-expanded="true" aria-controls="collapseOne">
                            {{$k}}
                        </button>
                    </h2>

                    <div class="accordion-collapse collapse row_{{$k}}" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
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
