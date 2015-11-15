@extends('layouts.master')

@section('content')
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
        <div class="page_header">
          <h1>Candidate Sentiment</h1>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-sm-12">
        <table class="table table-responsive">
          <thead>
            <tr>
              <th>Candidate</th>
              <th>Sentimate</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($candidates as $candidate)
              <tr>
                <td>{{{ $candidate->name }}}</td>
                <td>{{{ $candidate->average_sentiment * 100 }}}%</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
@stop