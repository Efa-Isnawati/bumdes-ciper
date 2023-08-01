@extends('layouts.admin')

@section('title')
    Kelola Reviews
@endsection
@section('content')
          <div
            class="section-content section-dashboard-home"
            data-aos="fade-up"
          >
            <div class="container-fluid">
              <div class="dashboard-heading">
                <h2 class="dashboard-title">Reviews</h2>
                <p class="dashboard-subtitle">
                  List of Reviews Product
                </p>
              </div>
              <div class="dashboard-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                        <a href="{{ route('reviews.print') }}" class="btn btn-primary" target="_blank">
                            <i class="fas fa-print"></i> Cetak Laporan
                        </a>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover scroll-horizontal-vertical w-100" id="crudTable">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Product</th>
                                                <th>User</th>
                                                <th>Rating</th>
                                                <th>Comment</th>
                                                <th>Created At</th>
                                                <th>Updated At</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($reviews as $review)
                                                <tr>
                                                    <td>{{ $review->id }}</td>
                                                    <td>{{ $review->product->name }}</td>
                                                    <td>{{ $review->user->name }}</td>
                                                    <td>{{ $review->rating }}</td>
                                                    <td>{{ $review->comment }}</td>
                                                    <td>{{ $review->created_at }}</td>
                                                    <td>{{ $review->updated_at }}</td>

                                                </tr>
                                                @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
              </div>
            </div>
          </div>
@endsection
@push('addon-script')
    <script>
        //AJAX DataTable
       var datatable = $('#crudTable').DataTable({
    processing: true,
    serverSide: true,
    ordering: true,
    ajax: {
        url: '{!!  url()->current() !!}',
    },
    columns: [
        { data: 'id', name: 'id' },
        { data: 'product_id', name: 'product_id' },
        { data: 'user_id', name: 'user_id' },
        { data: 'rating', name: 'rating' },
        { data: 'comment', name: 'comment' },
        { data: 'action', name: 'action', orderable: false, searchable: false, width: '15%' }
    ]
});

    </script>
    
@endpush