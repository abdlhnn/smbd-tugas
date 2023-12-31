<divv class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-white">
                <div class="row ">
                    <div class="col-md-6"><h3 class="fw-bold">Product List</h3></div>
                    <div class="col-md-6"><input wire:model="search" type="text" class="form-control" placeholder="Search Products....."></div>
                </div>
            </div>
            <div class="card-body">

                <div class="row">
                    @forelse ($products as $product)


                    <div class="col-md-3 mb-3" :key={{$product->id}}>
                        <div class="card" wire:click="addItem({{ $product->id}})" style="cursor: pointer">
                                <img src="{{ asset('storage/images/'.$product->image)}}" alt="product" style="object-fit:contain;width:100% ;height:150px">
                                <button wire:click="addItem({{ $product->id}})" class="btn btn-primary btn-sm " style="position:absolute;top:0;right:0"><i class="fas fa-cart-plus"></i></button>
                                <h6 class="text-center font-weight-bold">{{$product->name}}</h6>
                                <h6 class="text-center font-weight-bold" style="color: grey">Rp {{ number_format($product->price,2,',','.')}}</h6>
                        </div>
                    </div>

                    @empty
                    <h3 class="text-center fw-bold text-primary mt-3">No Products Found</h3>
                    @endforelse
                </div>
                <div class="d-flex justify-content-center">
                    {{$products->links()}}
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-white">
                <h3 class="fw-bold">Cart</h3>
            </div>
            <div class="card-body">
                @if (session()->has('error'))
                    <p class="text-danger fw-bold">
                    {{session('error')}}
                    </p>

                 @endif
                <table class="table table-sm table-bordered  table-hovered">
                    <thead class="bg-white ">
                        <tr>
                            <th class="fw-bold">No</th>
                            <th class="fw-bold">Name</th>
                            <th class="fw-bold">Qty</th>
                            <th class="fw-bold">Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($carts as $index=>$cart )
                        <tr>
                            <td>
                                {{$index + 1}}
                                <br>
                                <i class="fas fa-trash" wire:click="removeItem('{{$cart['rowId']}}')"  style="font-size: 12px ;cursor:pointer ;color:red"></i>
                            </td>
                            <td>
                                <a href="#" class="fw-bold text-dark">{{$cart['name']}}</a>
                            <br>
                            <a href="" class=" text-secondary">Rp {{ number_format($cart['pricesingle'],2,',','.')}}</a>
                            </td>

                            <td>
                                <button class="btn btn-primary btn-sm" style="padding: 1px 5px" wire:click="decreaseItem('{{$cart['rowId']}}')"><i class="fas fa-minus"  ></i></button>
                                  {{ $cart['qty'] }}
                                <button class="btn btn-primary btn-sm" style="padding: 1px 5px" wire:click="increaseItem('{{$cart['rowId']}}')"><i class="fas fa-plus"  ></i></button>
                            </td>
                            <td> Rp {{ number_format($cart['price'],2,',','.')}}</td>
                        </tr>

                        @empty
                            <td colspan="4"><h6 class="text-center">Empty Cart</h6></td>

                        @endforelse

                    </tbody>
                </table>

            </div>
        </div>
        <div class="card mt-4">
            <div class="card-body">
                <h4 class="fw-bold">Cart Summary</h4>
                <h5 class="fw-bold">Sub Total: Rp {{number_format($summary['sub_total'],2,',','.')}} </h5>
                <h5 class="fw-bold">Tax: Rp {{number_format($summary['pajak'],2,',','.')}}</h5>
                <h5 class="fw-bold">Total: Rp {{number_format($summary['total'],2,',','.')}}</h5>
                <div class="row">
                    <div class="col-sm-6">
                        <button wire:click="enableTax" class="btn btn-primary btn-block btn-sm">Add Tax</button>
                    </div>
                    <div class="col-sm-6">
                        <button wire:click="disableTax" class="btn btn-info btn-block btn-sm">Remove Tax</button>
                    </div>

                </div>
                <div class="form-group mt-4">
                    <input type="number"  class="form-control"  id="payment" placeholder="Input costumer payment amount">
                    <input type="hidden" id="total" value="{{$summary['total']}}">
                </div>

               <form wire:submit.prevent="handleSubmit">
                   <div>
                      <label >payment</label>
                      <h1 id="paymentText" >Rp. 0 </h1>
                   </div>

                   <div>
                      <label >Kembalian</label>
                      <h1 id="kembalianText" >Rp. 0 </h1>
                   </div>
                   <div class="mt-4">
                      <button wire:ignore type="submit" id="saveButton" disabled class="btn btn-success  btn-block"> <i class="fas fa-save"></i> Save Transaction </button>
                   </div>
               </form>
            </div>
        </div>
    </div>
</divv>

@push('script-custom')
<script>
    payment.oninput = () => {
        const paymentAmount = document.getElementById("payment").value
        const totaltAmount = document.getElementById("total").value

        const kembalian = paymentAmount - totaltAmount
        document.getElementById("kembalianText").innerHTML = `Rp ${rupiah(kembalian)} ,00`
        document.getElementById("paymentText").innerHTML = `Rp ${rupiah(paymentAmount)} ,00`

        const saveButton = document.getElementById("saveButton")

        if(kembalian < 0){
            saveButton.disabled = true
        }else{
            saveButton.disabled = false
        }
    }
    const rupiah = (angka) => {
        const numberString = angka.toString()
        const split = numberString.split(',')
        const sisa = split[0].length % 3
        let rupiah = split[0].substr(0, sisa)
        const ribuan = split[0].substr(sisa).match(/\d{1,3}/gi)

        if(ribuan){
            const separator = sisa ? '.' : ''
            rupiah += separator + ribuan.join('.')

        }
        return split[1] != undefined ? rupiah + ',' + split[1] : rupiah
    }
</script>

@endpush
