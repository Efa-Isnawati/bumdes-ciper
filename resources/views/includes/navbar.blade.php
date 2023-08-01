<nav class="navbar navbar-expand-lg navbar-light navbar-store fixed-top navbar-fixed-top" data-aos="fade-down">
    <div class="container">
        <a href="{{ route('home') }}" class="navbar-brand">
            <img src="/images/logos.svg" alt="Logo" />
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ml-auto">
                <form action="{{ route('search') }}" method="GET" class="form-inline my-2 my-lg-0">
                    <input type="text" name="query" placeholder="Search products" class="form-control mr-sm-2">
                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                </form>
                <li class="nav-item mr-3">
                    <a href="{{ route('home') }}" class="nav-link pt-4">Home</a>
                </li>
                <li class="nav-item mr-3">
                    <a href="{{ route('categories') }}" class="nav-link pt-4">Categories</a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link pt-4" id="info-button">Info</a>
                    <div class="popup-card" id="info-card">
                        <div class="popup-card-content">
                            Ayo belanja di Bumdes Store
                            Potongan harga 10%<br>
                            !! Alert !!<br>
                            Untuk produk makanan khusus makan ditempat ya
                        </div>
                    </div>
                </li>
                @auth
                    <li class="nav-item">
                        <a href="#" class="nav-link pt-4" id="notification-button">
                            @php
                                $transactionCount = \App\Models\Transaction::where('users_id', Auth::user()->id)->count();
                                $notif = ($transactionCount > 2);
                            @endphp

                            @if($notif > 0)
                                <img src="/images/notification.svg" alt="" />
                                <div class="card-badge">{{ $notif }}</div>
                            @else
                                <img src="/images/notification.svg" alt="" />
                            @endif

                            {{-- <img src="/images/notification.svg" alt="" />
                             <div class="card-badge">{{ $ }}</div> --}}

                            <!-- Tambahkan kode notifikasi yang belum dibaca -->
                            {{-- @auth
                                @if(auth()->user()->unreadNotifications->count() > 0)
                                    <span class="badge badge-danger">{{ auth()->user()->unreadNotifications->count() }}</span>
                                @endif
                            @endauth --}}
                        </a>
                    </li>
                @endauth

                @guest
                    <li class="nav-item">
                        <a href="{{ route('register') }}" class="nav-link pt-4">Sign Up</a>
                    </li>
                    <li class="nav-item pt-3">
                        <a href="{{ route('login') }}" class="btn btn-success nav-link px-4 text-white">Sign In</a>
                    </li>
                @endguest

                @auth
                    <!-- Dropdown User -->
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a href="#" class="nav-link" id="navbarDropdown" role="button" data-toggle="dropdown">
                                <img src="/images/icons-testimonial-2.png" alt="" class="rounded-circle mr-2 profile-picture" />
                                Hi, {{ Auth::user()->name }}
                            </a>
                            <div class="dropdown-menu">
                                <a href="{{ route('dashboard') }}" class="dropdown-item">Dashboard</a>
                                <a href="{{ route('dashboard-settings-account') }}" class="dropdown-item">Settings</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('cart') }}" class="nav-link d-inline-block mt-2">
                                @php
                                    $carts = \App\Models\Cart::where('users_id', Auth::user()->id)->count();
                                @endphp
                                @if($carts > 0)
                                    <img src="/images/icon-cart-filled.svg" alt="" />
                                    <div class="card-badge">{{ $carts }}</div>
                                @else
                                    <img src="/images/icon-cart-empty.svg" alt="" />
                                @endif
                            </a>
                        </li>
                    </ul>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<!-- Modal Notifikasi -->
<div class="modal fade" id="notification-modal" tabindex="-1" role="dialog" aria-labelledby="notification-modal-label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="notification-modal-label">Notifikasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Isi notifikasi di sini -->
                {{-- @auth
                    @if(auth()->user()->unreadNotifications->count() > 0)
                        @foreach(auth()->user()->unreadNotifications as $notification)                           
                            @if($notification->data['message'] == 'Selamat, Anda telah melakukan lebih dari 2x transaksi dan mendapatkan merchandise Bumdes Store')
                                <p>{{ $notification->data['message'] }}</p>
                            @endif
                        @endforeach
                    @endif
                @endauth --}}
                @inject('NotificationController', 'App\Http\Controllers\NotificationController')
                @if($NotificationController->getNotificationMerchandise() > 2)
                    <p>Selamat, Anda telah melakukan lebih dari 2x transaksi dan mendapatkan merchandise dari Bumdes Store pengiriman dilakukan ketika anda transaksi kembali</p>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<style>
    .popup-card {
        position: relative;
        display: inline-block;
    }

    .popup-card-content {
        visibility: hidden;
        opacity: 0;
        position: absolute;
        background-color: #fff;
        width: 200px;
        padding: 5px;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
        transition: opacity 0.3s ease;
        z-index: 1;
        top: 100%;
        left: 50%;
        transform: translateX(-50%);
    }

    .popup-card.show .popup-card-content {
        visibility: visible;
        opacity: 1;
    }
</style>

<!-- Tambahkan skrip jQuery dan Bootstrap untuk mengaktifkan modal -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    document.getElementById('info-button').addEventListener('click', function() {
        document.getElementById('info-card').classList.toggle('show');
    });

    // Tampilkan popup notifikasi saat tombol notifikasi diklik
    $(document).ready(function() {
        $('#notification-button').on('click', function() {
            $('#notification-modal').modal('show');
        });
    });
</script>



{{-- <script>
    // Tampilkan popup notifikasi saat tombol notifikasi diklik
    $(document).ready(function() {
        $('#notification-button').on('click', function() {
            $('#notification-modal').modal('show');
        });
    });

    // Cek apakah pengguna memenuhi syarat untuk notifikasi merchandise
    @auth
        var transactionCount = {{ $transactionCount ?? 0 }};
        if (transactionCount >= 2) {
            // Kirim notifikasi "Selamat, Anda telah melakukan lebih dari 2x transaksi dan mendapatkan merchandise Bumdes Store"
            var notification = {
                message: 'Selamat, Anda telah melakukan lebih dari 2x transaksi dan mendapatkan merchandise Bumdes Store'
            };
            $.ajax({
                url: '{{ route('send.notification') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    notification: notification
                },
                success: function(response) {
                    console.log(response);
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }
    @endauth
</script> --}}
{{-- @auth
    @php
        $transactionCount = 3; // Ubah dengan nilai yang sesuai
    @endphp

    <script>
    function showNotifications() {
        // Tampilkan notifikasi di sini
        @auth
            @if(Auth::user()->unreadNotifications->count() > 0)
                var notifications = @json(Auth::user()->unreadNotifications);
                var notificationList = '';
                notifications.forEach(function(notification) {
                    notificationList += '<p>' + notification.data.message + '</p>';
                });
                // Ganti "notification-modal-body" dengan ID elemen yang sesuai di modal notifikasi
                document.getElementById('notification-modal-body').innerHTML = notificationList;
                // Tampilkan modal notifikasi
                $('#notification-modal').modal('show');
            @endif
        @endauth
    }
</script>
@endauth --}}



<script src="/vendor/jquery/jquery.slim.min.js"></script>
<script src="/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>