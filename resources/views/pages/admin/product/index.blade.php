{{-- @extends('layouts.admin')

@section('title')
    Products
@endsection

@section('content')
          <div
            class="section-content section-dashboard-home"
            data-aos="fade-up"
          >
            <div class="container-fluid">
              <div class="dashboard-heading">
                <h2 class="dashboard-title">Product</h2>
                <p class="dashboard-subtitle">
                  List of Products
                </p>
              </div>
              <div class="dashboard-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <a href="{{route('product.create')}}" class="btn btn-primary mb-3">
                                    + Tambah Product Baru
                                </a>
                                <div class="table-responsive">
                                    <table class="table table-hover scroll-horizontal-vertical w-100" id="crudTable">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Nama</th>
                                                <th>Pemilik</th>
                                                <th>Kategori</th>
                                                <th>Harga</th>
                                                <th>Aksi</th>

                                            </tr>
                                        </thead>
                                        <tbody>

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
            proccessing: true,
            serverSide: true,
            ordering: true,
            ajax:{
                url:'{!! url()->current() !!}',
            },
            columns:[
                {data: 'id', name:'id'},
                {data: 'name', name:'name'},
                {data: 'user.name', name:'user.name'},
                {data: 'category.name', name:'category.name'},
                {data: 'price', name:'price'},
                {
                    data: 'action', 
                    name:'action',
                    orderable: false,
                    searchable:false,
                    width:'15%'
            },
        ]
        });
    </script>
    
@endpush --}}
@extends('layouts.admin')

@section('title')
    Dashboard Product
@endsection

@section('content')
    <!-- Section Content -->
          <div
            class="section-content section-dashboard-home"
            data-aos="fade-up"
          >
            <div class="container-fluid">
              <div class="dashboard-heading">
                <h2 class="dashboard-title">My Products</h2>
                <p class="dashboard-subtitle">
                  Manage it well and get money
                </p>
              </div>
              <div class="dashboard-content">
                <div class="row">
                  <div class="col-12">
                    <a
                      href="{{ route('product-create') }}"
                      class="btn btn-success"
                      >Add New Product</a
                    >
                  </div>
                </div>
                <div class="row mt-4">

                  @foreach ($products as $product)
                      <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                        <a
                          href="{{ route('dashboard-product-details', $product->id) }}"
                          class="card card-dashboard-product d-block"
                        >
                          <div class="card-body">
                            <img
                              src="{{ Storage::url($product->galleries->first()->photos ?? '') }}"
                              alt=""
                              class="w-100 mb-2"
                            />
                            <div class="product-stock">Stok: {{ $product->stock }}</div>
                            <div class="product-title">{{ $product->name }}</div>
                            <div class="product-category">{{ $product->category->name }}</div>
                            <form action="{{ route('dashboard-product-delete', $product->id) }}" method="POST"
                                          class="d-inline">
                                        @method('DELETE')  
                                        @csrf
                                        <button type="submit" class="btn btn-outline-danger mt-2">Delete</button>
                            </form>
                          </div>
                        </a>
                      </div>
                  @endforeach

                </div>
              </div>
            </div>
          </div>
@endsection