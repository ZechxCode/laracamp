@extends('layouts.app')

@section('content')
    <section class="my-5 dashboard">
        <div class="container">
            <div class="text-left row">
                <div class="mt-4 col-lg-12 col-12 header-wrap">
                    <p class="story">
                        DASHBOARD
                    </p>
                    <h2 class="primary-header ">
                        My Bootcamps
                    </h2>
                </div>
            </div>

            <div class="my-5 row">
                @include('components.alert')
                <table class="table">
                    <tbody>
                        @forelse ($checkouts as $checkout)
                            <tr class="align-middle">
                                <td width="18%">
                                    <img src="/images/item_bootcamp.png" height="120" alt="">
                                </td>
                                <td>
                                    <p class="mb-2">
                                        <strong>{{ $checkout->Camp->title }}</strong>
                                    </p>
                                    <p>
                                        {{ $checkout->created_at->format('F d, Y') }}
                                    </p>

                                    {{-- <p class="fs-6">Expired At
                                        {{ \Carbon\Carbon::createFromFormat('Y-m-d', $checkout->expired)->format('F d, Y') }}
                                    </p> --}}

                                </td>
                                <td>
                                    <strong>$280,000</strong>
                                </td>
                                <td>
                                    @if ($checkout->payment_status == 'paid')
                                        <strong><span class="text-green">Payment Success</span></strong>
                                    @elseif ($checkout->payment_status == 'pending' || $checkout->payment_status == 'waiting')
                                        <strong><span class="text-secondary">{{ $checkout->payment_status }}</span></strong>
                                    @elseif (
                                        $checkout->payment_status == 'failed' ||
                                            $checkout->payment_status == 'failure' ||
                                            $checkout->payment_status == 'cancel')
                                        <strong><span class="text-red ">{{ $checkout->payment_status }}</span></strong>
                                    @endif
                                </td>
                                <td>
                                    @if ($checkout->payment_status == 'pending' || $checkout->payment_status == 'waiting')
                                        <a href="{{ $checkout->midtrans_url }}" target="_blank" class="btn btn-primary">Pay
                                            Here</a>
                                    @endif
                                </td>
                                <td>
                                    <a href="http://wa.me/085348502700?text=Hi, saya ingin bertanya tentang kelas {{ $checkout->Camp->title }}"
                                        class="btn btn-primary">
                                        Contact Support
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <h3>No Camp Registered</h3>
                                </td>
                            </tr>
                        @endforelse


                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
