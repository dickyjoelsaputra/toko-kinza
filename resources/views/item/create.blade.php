@extends('layouts.app')

@section('title', 'Tambah Barang')

@push('style')
<style>
    /* KAMERA START */

    .camera-container {
        position: relative;
        padding-bottom: 100%;
        /* Mengubah proporsi menjadi 1:1 */
        height: 0;
        overflow: hidden;
    }

    .camera-container video {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .photo-container {
        width: 100%;
        height: 0;
        padding-bottom: 100%;
        /* Mengubah proporsi menjadi 1:1 */
        position: relative;
        overflow: hidden;
    }

    .photo-container img {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 100%;
        height: auto;
    }

    /* KAMERA END */


    .input-group-addon {
        border-left-width: 0;
        border-right-width: 0;
    }

    .input-group-addon:first-child {
        border-left-width: 1px;
    }

    .input-group-addon:last-child {
        border-right-width: 1px;
    }

    .input-group .icon {
        background-color: red;
        color: white;
    }

    .okcard .col {
        padding: 10px;
    }

    .cross-icon {
        position: absolute;
        top: 10px;
        right: 10px;
        width: 30px;
        height: 30px;
        background-color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
@endpush

@section('main')
<link rel="stylesheet"
    href="{{ asset('library/select2/dist/css/select2.min.css') }}">
@include('components._toast')

<div class="main-content">
    <div class="card">
        <div class="card-header">
            <h4>Tambah Barang</h4>
        </div>
        <div class="mx-3">
            <div class="card-body p-0 mb-4">
                {{-- START --}}
                <div class="row">
                    <div class="col-sm">
                        <div class="mb-3 d-sm-none" id="reader" width="600px"></div>
                        <div class="form-group">
                            <label>Scan Barcode</label>
                            <input id="code" name="code" type="text" placeholder="kosongkan jika tidak ada barcode"
                                class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Nama Barang</label>
                            <input id="name" name="name" type="text" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Harga Modal</label>
                            <input id="capital" name="capital" type="text" class="form-control capital">
                        </div>

                        <div class="harga-wrapper">
                            <div class="form-group">
                                <label>Harga Jual dan Unit</label>
                                <div class="form-row">
                                    <div class="col-md-4 col-8 mb-2">
                                        <input name="[]price" type="text" class="form-control price"
                                            placeholder="Harga">
                                    </div>
                                    <div class="col-md-3 col-4 mb-2">
                                        <input name="[]minimal" type="text" class="form-control" placeholder="Minimal">
                                    </div>
                                    <div class="col-md-4 col-9 mb-2">
                                        <select class="form-control select2" name="unit">
                                            <option value="" selected disabled>Pilih Satuan</option>
                                            @foreach ($units as $unit)
                                            <option value="{{ $unit->id }}">{{ $unit->name }} ||
                                                {{ $unit->alias }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-1 col-3 mb-2">
                                        <div class="deletePrice bg-danger d-flex align-items-center justify-content-center border rounded"
                                            style="width: 100%; height: 100%; cursor: pointer;">
                                            <i class="fas fa-trash text-white"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- Button Start --}}
                        <div class="d-flex flex-column align-items-center">
                            <button type="button" id="priceBtn" class="btn btn-primary mt-3">Tambah Harga Jual</button>
                        </div>
                        {{-- Button End --}}

                    </div>
                </div>

            </div>
            <hr>
            <div class="card-body">
                <div class="col-sm">
                    <div class="row">
                        <div class="col-md-6 text-center">
                            <h4>Akses Kamera</h3>
                                <div id="cameraView" class="camera-container">
                                    <video id="videoElement"></video>
                                </div>
                                <div class="d-flex flex-column align-items-center">
                                    <button type="button" id="startCameraBtn" class="btn btn-primary mt-3">Akses
                                        Kamera</button>
                                    <button type="button" id="stopCameraBtn" class="btn btn-danger mt-3"
                                        style="display: none;">Berhenti</button>
                                </div>
                                <div class="d-flex flex-column align-items-center">
                                    <button type="button" id="captureBtn" class="btn btn-primary mt-3">Ambil
                                        Foto</button>
                                </div>
                        </div>
                        <div class="col-md-6 mt-3 text-center">
                            <h4>Hasil Jepretan</h4>
                            <div id="photoPreview" class="photo-container"></div>
                            <div class="d-flex flex-column align-items-center">
                                <button type="button" id="deleteBtn" class="btn btn-danger mt-3 mx-auto"
                                    style="display: none;">Hapus
                                    Foto</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="d-flex flex-column align-items-center mb-5">
                <button type="button" id="tambah" class="btn btn-success mt-3">Tambah Barang</button>
            </div>
            </form>
            {{-- END --}}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
    $(document).ready(function() {

        // START SCANNER
        function playSuccessSound() {
            const successSound = new Audio("{{ asset('asset/sound/mixkit-game-notification-wave-alarm-987.wav') }}");
            console.log(successSound);
            successSound.play();
        }

        function onScanSuccess(decodedText, decodedResult) {
            $("#code").val(decodedText);
            playSuccessSound();
        }

        function onScanFailure(error) {
        // console.warn(`Code scan error = ${error}`);
        }

        let html5QrcodeScanner = new Html5QrcodeScanner(
            "reader", {
            fps: 10,
            qrbox: {
                width: 250,
                height: 100
                }
            },false);
        html5QrcodeScanner.render(onScanSuccess, onScanFailure);
        // END SCANNER

        // START AKES CAMERA
        $("#cameraView").hide();
        $("#captureBtn").hide();
        $('#photoPreview').hide();
        disableCamera();
        $("#startCameraBtn").click(function() {
            $("#cameraView").show();
            $("#stopCameraBtn").show();
            $("#startCameraBtn").hide();
            $("#captureBtn").show();
            // $("#deleteBtn").hide();
            enableCamera();
        });

        $("#stopCameraBtn").click(function() {
            $("#cameraView").hide();
            $("#stopCameraBtn").hide();
            $("#startCameraBtn").show();
            $("#captureBtn").hide();
            // $("#deleteBtn").hide();
            disableCamera();
        });

        function enableCamera() {
            navigator.mediaDevices
            .getUserMedia({ video: { facingMode: "environment" } })
            .then(function(stream) {
                var video = document.getElementById("videoElement");
                video.autoplay = true;
                video.srcObject = stream;
            })
            .catch(function(error) {
                console.log("Error accessing camera: " + error.message);
            });
        }

        function disableCamera() {
            var video = document.getElementById("videoElement");
            if (video.srcObject) {
                var tracks = video.srcObject.getTracks();
                tracks.forEach(function(track) {
                track.stop();
            });
            video.srcObject = null;
            }
        }

        // END AKSES CAMERA


        // PRICE START
        $(document).on('input', '.price', function() {
            var harga = $(this).val();
            harga = harga.replace(/[^0-9]/g, '');
            var formattedHarga = new Intl.NumberFormat('id-ID').format(harga);
            $(this).val(formattedHarga);
        });
        // PRICE END

        // CAPITAL START
        $(document).on('input', '.capital', function() {
            var harga = $(this).val();
            harga = harga.replace(/[^0-9]/g, '');
            var formattedHarga = new Intl.NumberFormat('id-ID').format(harga);
            $(this).val(formattedHarga);
        });
        // CAPITAL END

        // FOCUS START
        $("#code").focus();
        // FOCUS END

        // CAMERA START
        // navigator.mediaDevices.getUserMedia({
        //         video: { facingMode: 'environment' }
        //     }).then(function(stream) {
        //         var video = document.getElementById('videoElement');
        //         video.autoplay = true;
        //         video.srcObject = stream;
        //     }).catch(function(error) {
        //     console.log('Error accessing camera: ' + error.message);
        // });

        $('#captureBtn').click(function() {
            var video = document.getElementById('videoElement');
            var canvas = document.createElement('canvas');
            var context = canvas.getContext('2d');

            var width = video.videoWidth;
            var height = video.videoHeight;

            var squareSize = Math.min(width, height);

            canvas.width = squareSize;
            canvas.height = squareSize;

            var xOffset = (width - squareSize) / 2;
            var yOffset = (height - squareSize) / 2;

            context.drawImage(video, xOffset, yOffset, squareSize, squareSize, 0, 0, squareSize,
            squareSize);

            var imgData = canvas.toDataURL();
            var img = document.createElement('img');
            img.src = imgData;
            img.classList.add('img-fluid');
            img.classList.add('myImage');
            $('#photoPreview').show()
            $('#photoPreview').empty().append(img);
            $('#deleteBtn').show();

        });

        $('#deleteBtn').click(function() {
            $('#photoPreview').empty();
            $('#photoPreview').hide()
            $('#deleteBtn').hide();
        });
        // CAMERA END

        // TAMBAH HARGA START
        $('#priceBtn').click(function() {
            var formGroup = $('<div class="form-group"></div>');
            var formGroupContent = `<label>Harga dan Unit</label>
            <div class="form-row">
                <div class="col-md-4 col-8 mb-2">
                    <input name="[]price" type="text" class="form-control price"
                        placeholder="Harga">
                </div>
                <div class="col-md-3 col-4 mb-2">
                    <input name="[]minimal" type="text" class="form-control" placeholder="Minimal">
                </div>
                <div class="col-md-4 col-9 mb-2">
                    <select class="form-control select2" name="unit">
                        <option value="" selected disabled>Pilih Satuan</option>
                        @foreach ($units as $unit)
                        <option value="{{ $unit->id }}">{{ $unit->name }} ||
                            {{ $unit->alias }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-1 col-3 mb-2">
                    <div class="deletePrice bg-danger d-flex align-items-center justify-content-center border rounded"
                        style="width: 100%; height: 100%; cursor: pointer;">
                        <i class="fas fa-trash text-white"></i>
                    </div>
                </div>
            </div>
                `;
            formGroup.append(formGroupContent);
            $('.harga-wrapper').append(formGroup);
        });
        // TAMBAH HARGA END

        // HAPUS HARGA STAR
        $(document).on('click', '.deletePrice', function() {
            var formGroup = $(this).closest('.form-group');
            formGroup.remove();
        });
        // HAPUS HARGA END

        // TAMBAH BARANG START
        $('#tambah').click(function() {
            var code = $('#code').val();
            var name = $('#name').val();
            var capital = $('#capital').val();
            var dataPrice = $('.price');
            var priceInputs = $('input[name="[]price"]');
            var minimalInputs = $('input[name="[]minimal"]');
            var unitSelects = $('select[name="unit"]');
            var data = [];

            dataPrice.each(function(index) {
                var price = priceInputs.eq(index).val();
                var minimal = minimalInputs.eq(index).val();
                var unit = unitSelects.eq(index).val();

                var item = {
                    price: price,
                    minimal: minimal,
                    unit: unit
                };

                data.push(item);
            });

            var imageBase64 = '';
            var imageElement = $('#photoPreview').find('img');
            if (imageElement.length > 0) {
                imageBase64 = imageElement.attr('src');
            }

            var requestData = {
                code: code,
                name: name,
                capital: capital,
                items: data,
                image: imageBase64
            };

            // AJAX REQUEST START
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            $.ajax({
                url: '/item/create-ajax',
                type: 'POST',
                data: requestData,
                success: function(response) {
                    showToast(response.message, 'success' );

                    $('#code').val('');
                    $('#name').val('');
                    $('.harga-wrapper').empty();
                    $('#photoPreview').empty();
                    $('#photoPreview').hide();
                    $('#deleteBtn').hide();
                    // trigger
                    $('#stopCameraBtn').trigger('click');
                    $('#priceBtn').trigger('click');
                    $("#code").focus();
                },
                error: function(xhr) {
                    var errors = xhr.responseJSON.message;
                    var errorMessage = "<ul>";
                    if (Array.isArray(errors)) {
                        for (var i = 0; i < errors.length; i++) {
                            errorMessage +="<li>" + errors[i] + "</li>" ;
                        }
                    }else{
                        errorMessage += "<li>" + errors + "</li>";
                    }

                    errorMessage += "</ul>";

                    showToast(errorMessage, 'danger' );
                    $("#code").focus();
                }
            });
            // AJAX REQUEST END


        });
        // TAMBAH BARANG END
    });
</script>
@endpush
