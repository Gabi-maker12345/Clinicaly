<x-form-section submit="updateProfileInformation">
    <link rel="stylesheet" href="{{ asset('css/clinicaly.css') }}">
    <x-slot name="title">
        {{ __('Profile Information') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Update your account\'s profile information and email address.') }}
    </x-slot>

    <x-slot name="form">
        <!-- Profile Photo -->
        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
            <div
                x-data="{
                    photoName: null,
                    photoPreview: null,
                    photoUploading: false,
                    photoProgress: 0,
                    photoUploadError: '',
                    photoReady: false,
                    async preparePhoto(event) {
                        const original = event.target.files[0];

                        if (!original) return;

                        this.photoName = original.name;
                        this.photoUploadError = '';
                        this.photoReady = false;
                        this.photoProgress = 0;
                        this.photoPreview = URL.createObjectURL(original);
                        this.photoUploading = true;

                        try {
                            const file = await this.compressPhoto(original);
                            const data = new FormData();

                            data.append('photo', file);

                            const response = await fetch('{{ route('profile.photo.update') }}', {
                                method: 'POST',
                                headers: {
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name=&quot;csrf-token&quot;]').content,
                                },
                                body: data,
                            });

                            const payload = await response.json().catch(() => ({}));

                            if (!response.ok) {
                                throw new Error(payload.message || 'Não foi possível salvar a foto.');
                            }

                            this.photoPreview = payload.url || this.photoPreview;
                            this.photoUploading = false;
                            this.photoReady = true;
                            this.photoProgress = 100;
                            this.$refs.photo.value = '';

                            document.querySelectorAll('img[data-profile-photo], .user-chip img, .side-profile img').forEach((image) => {
                                image.src = this.photoPreview;
                            });
                        } catch (error) {
                            this.photoUploading = false;
                            this.photoReady = false;
                            this.photoUploadError = error.message || 'Não foi possível preparar a foto.';
                            this.$refs.photo.value = '';
                        }
                    },
                    compressPhoto(file) {
                        if (!file.type.startsWith('image/')) {
                            throw new Error('Selecione uma imagem em JPG ou PNG.');
                        }

                        return new Promise((resolve, reject) => {
                            const image = new Image();
                            const url = URL.createObjectURL(file);

                            image.onload = async () => {
                                try {
                                    URL.revokeObjectURL(url);

                                    const maxSize = 1200;
                                    const scale = Math.min(1, maxSize / Math.max(image.width, image.height));
                                    let canvas = document.createElement('canvas');
                                    canvas.width = Math.max(1, Math.round(image.width * scale));
                                    canvas.height = Math.max(1, Math.round(image.height * scale));

                                    const context = canvas.getContext('2d');
                                    context.drawImage(image, 0, 0, canvas.width, canvas.height);

                                    const toBlob = (source, quality) => new Promise((done) => {
                                        source.toBlob(done, 'image/jpeg', quality);
                                    });

                                    let quality = 0.82;
                                    let blob = await toBlob(canvas, quality);
                                    const maxBytes = 1800 * 1024;

                                    while (blob && blob.size > maxBytes && canvas.width > 420 && canvas.height > 420) {
                                        if (quality > 0.58) {
                                            quality -= 0.08;
                                        } else {
                                            const next = document.createElement('canvas');
                                            next.width = Math.max(1, Math.round(canvas.width * 0.82));
                                            next.height = Math.max(1, Math.round(canvas.height * 0.82));
                                            next.getContext('2d').drawImage(canvas, 0, 0, next.width, next.height);
                                            canvas = next;
                                            quality = 0.78;
                                        }

                                        blob = await toBlob(canvas, quality);
                                    }

                                    if (!blob) {
                                        reject(new Error('Não foi possível processar a imagem.'));
                                        return;
                                    }

                                    if (blob.size > maxBytes) {
                                        reject(new Error('A foto ainda ficou grande demais. Tente outra imagem JPG ou PNG.'));
                                        return;
                                    }

                                    resolve(new File([blob], this.photoName.replace(/\.[^.]+$/, '.jpg'), {
                                        type: 'image/jpeg',
                                        lastModified: Date.now(),
                                    }));
                                } catch (error) {
                                    reject(error);
                                }
                            };

                            image.onerror = () => {
                                URL.revokeObjectURL(url);
                                reject(new Error('Não foi possível ler esta imagem. Use JPG ou PNG.'));
                            };

                            image.src = url;
                        });
                    },
                }"
                class="col-span-6 sm:col-span-4"
            >
                <!-- Profile Photo File Input -->
                <input type="file" id="photo" class="hidden"
                            accept="image/png,image/jpeg"
                            x-ref="photo"
                            x-on:change="preparePhoto($event)" />

                <x-label for="photo" value="{{ __('Photo') }}" />

                <!-- Current Profile Photo -->
                <div class="mt-2" x-show="! photoPreview">
                    <img data-profile-photo src="{{ $this->user->profile_photo_url }}" alt="{{ $this->user->name }}" class="rounded-full size-20 object-cover">
                </div>

                <!-- New Profile Photo Preview -->
                <div class="mt-2" x-show="photoPreview" style="display: none;">
                    <img x-bind:src="photoPreview" alt="Prévia da nova foto" style="width:80px;height:80px;border-radius:999px;object-fit:cover;border:3px solid var(--bd);box-shadow:var(--sh);">
                </div>

                <x-secondary-button class="mt-2 me-2" type="button" x-on:click.prevent="$refs.photo.click()">
                    {{ __('Select A New Photo') }}
                </x-secondary-button>

                @if ($this->user->profile_photo_path)
                    <x-secondary-button type="button" class="mt-2" wire:click="deleteProfilePhoto">
                        {{ __('Remove Photo') }}
                    </x-secondary-button>
                @endif

                <div class="mt-2" x-show="photoUploading" x-cloak>
                    <div style="height: 7px; border-radius: 999px; background: var(--sf3); overflow: hidden;">
                        <div style="height: 100%; background: var(--in); transition: width .2s;" x-bind:style="'width: ' + photoProgress + '%'"></div>
                    </div>
                    <p class="text-sm mt-1" style="color: var(--mu); font-weight: 700;">Carregando foto...</p>
                </div>

                <p class="text-sm mt-2" x-show="photoReady" style="display: none; color: var(--gr); font-weight: 700;">Foto atualizada.</p>
                <p class="text-sm mt-2" x-show="photoUploadError" x-text="photoUploadError" style="display: none; color: var(--rd); font-weight: 700;"></p>
                <x-input-error for="photo" class="mt-2" />
            </div>
        @endif

        <!-- Name -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="name" value="{{ __('Name') }}" />
            <x-input id="name" type="text" class="mt-1 block w-full" wire:model="state.name" autocomplete="name" placeholder="{{ $this->user->name }}" />
            <x-input-error for="name" class="mt-2" />
        </div>

        <!-- Email -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="email" value="{{ __('Email') }}" />
            <x-input id="email" type="email" class="mt-1 block w-full" wire:model="state.email" autocomplete="username" placeholder="{{ $this->user->email }}" />
            <x-input-error for="email" class="mt-2" />

            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::emailVerification()) && ! $this->user->hasVerifiedEmail())
                <p class="text-sm mt-2">
                    {{ __('Your email address is unverified.') }}

                    <button type="button" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" wire:click.prevent="sendEmailVerification">
                        {{ __('Click here to re-send the verification email.') }}
                    </button>
                </p>

                @if ($this->verificationLinkSent)
                    <p class="mt-2 font-medium text-sm text-green-600">
                        {{ __('A new verification link has been sent to your email address.') }}
                    </p>
                @endif
            @endif
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-action-message class="me-3" on="saved">
            {{ __('Saved.') }}
        </x-action-message>

        <x-button wire:loading.attr="disabled" wire:target="photo,updateProfileInformation">
            {{ __('Save') }}
        </x-button>
    </x-slot>
</x-form-section>
