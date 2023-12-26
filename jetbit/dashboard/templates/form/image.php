<?php
/**
 * Single image uploader.
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/fields/image.php
 *
 * TODO: add crop image functionality
 *
 * @version     1.0.0
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}

[$label, $label_class, $class] = array_values(
	( new Grafema\Sanitizer() )->apply(
		$args,
		[
			'label'       => 'trim',
			'label_class' => 'class:dg ga-1 fw-600',
			'class'       => 'class:dg',
		]
	)
);
?>
<div class="<?php echo $class; ?>">
	<div class="df aife g-4">
		<div class="image" x-data="avatar, tabs = 'upload'" x-init="content = 'Yan Aleksandrov'">
			<input type="file" id="fileInputs" x-ref="input" @change="add($event, () => $modal.open('crop-image'))" hidden>
			<span class="image__close" @click="remove" x-show="image" title="Remove image" x-cloak>
				<i class="ph ph-x"></i>
			</span>
			<div class="image__container">
				<label for="fileInputs">
					<span class="avatar avatar--lg avatar--rounded" :style="image && `background-image: url(${image})`">
						<span x-text="getInitials(content)" x-show="!image"></span>
					</span>
				</label>
				<span class="image__action" @click="$modal.open('take-selfie')" title="You can take a selfie. Allow the browser to access the camera."><i class="ph ph-webcam"></i></span>
			</div>
		</div>
		<div class="dg g-2 mw50x9">
			<?php if ( $label ) { ?>
				<div class="<?php echo $label_class; ?>"><?php echo $label; ?></div>
			<?php } ?>
			<div class="fs-13 t-muted lh-xs">
				<a @click.prevent="$refs.input.click()">Click to upload</a> WEBP, PNG, JPG or GIF (max. 400×400px)
			</div>
		</div>
	</div>

	<div class="modal" id="take-selfie" tabindex="-1" role="dialog" aria-hidden="true" x-cloak>
		<div class="modal__dialog modal__dialog--sm" role="document">
			<div class="modal__content" @click.outside="$modal.close()">
				<div x-data="{second: '', showImg: ''}">
					<div x-init="$stream.start($refs)" style="position: relative; overflow: hidden;">
						<video x-ref="video" class="db mw" autoplay style="object-fit: cover; aspect-ratio: 4/3;"></video>
						<canvas x-ref="canvas" x-show="!showImg" style="border-radius: 20rem; width: 240px; height: 240px; position: absolute; margin: auto; inset: 0; box-shadow: 0 0 0 999px rgb(255 255 255 / 60%);"></canvas>
						<img x-ref="image" x-show="showImg" x-cloak alt="" src="/dashboard/assets/images/1x1.png" style="border-radius: 20rem; width: 240px; height: 240px; position: absolute; margin: auto; inset: 0; box-shadow: 0 0 0 999px rgb(255 255 255 / 98%);">
					</div>
					<div class="modal__body bg-milky t-center" style="position: relative;">
						<div
							class="fs-48"
							x-show="second > 0"
							x-text="second"
							:style="second && 'position: fixed; top: 1rem; left: 0; right: 0; margin: 0 auto; transition: all 1s; animation: ticker 1s ease infinite;'"
						></div>

						<div x-show="!showImg">
							<h6>Center your face</h6>
							<div class="fs-14 t-muted mt-2 pl-4 pr-4">
								Align your face to the center of the selfie area and then take a photo
							</div>
							<div class="df jcsb mt-6 mw">
								<button type="button" class="btn btn--outline" @click="$modal.close(), $stream.stop()">Cancel</button>
								<button class="btn btn--primary" @click="$countdown.start(3, () => second = $countdown.second, () => showImg = $stream.snapshot($refs))">Take Selfie</button>
							</div>
						</div>

						<div x-show="showImg">
							<h6>Check quality</h6>
							<div class="fs-14 t-muted mt-2 pl-4 pr-4">
								Make sure your face is not blurred or out of the frame before continuing
							</div>
							<div class="df jcsb mt-6 mw">
								<button class="btn btn--outline" type="button" @click="showImg = ''"><i class="ph ph-arrows-clockwise"></i> Take a new</button>
								<button class="btn btn--primary" type="button" @click="showImg = ''"><i class="ph ph-user-focus"></i> Use this photo</button>
							</div>
						</div>
					</div>
				</div>
				<div style="position: absolute; right: 0; top: 0;">
					<button type="button" class="modal__close" @click="$modal.close(), $stream.stop()"></button>
				</div>
			</div>
		</div>
	</div>
</div>
