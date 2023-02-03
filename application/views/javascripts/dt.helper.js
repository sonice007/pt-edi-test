var Toast = Swal.mixin({
	toast: true,
	position: 'top-end',
	showConfirmButton: false,
	timer: 3000
});

// setInterval(function(){
// 	cekNotifikasi()	
// }, 10000);

function klikNotifikasi(){
	// clearNotifikasi()
}

function clearNotifikasi(){
	$.ajax({
		method: 'post',
		url: '<?php echo base_url();?>dashboard/clearNotifikasi',
		data: null
	}).done((data) => {
		$("#jumlah-head-notifikasi").text(0)
	})
}

function cekNotifikasi(){
	$("#list-notifikasi").empty()
	$.ajax({
		method: 'post',
		url: '<?php echo base_url();?>dashboard/getNotifikasi',
		data: null
	}).done((data) => {
		$("#jumlah-notifikasi").text(data.jumlah+' Notifikasi')
		$("#jumlah-head-notifikasi").text(data.jumlah_baru)
		data.data.forEach(e => {
			$("#list-notifikasi").append(''+
				'<div class="dropdown-divider"></div>'+
				'<a href="#" class="dropdown-item">'+
					'<i class="fas fa-envelope mr-2"></i> '+e.judul+'<br>'+
					'<span class="float-left text-muted text-sm">'+e.created_at+'</span>'+
				'</a><br>'
			)
		});
	})
}

function ratingToStar(rating) {
	if (rating == 0) {
		return `<svg width="57" height="9" viewBox="0 0 57 9" fill="none" xmlns="http://www.w3.org/2000/svg">
		<path d="M4.5 0L6.02963 2.60796L9 3.24671L6.975 5.49727L7.28115 8.5L4.5 7.28296L1.71885 8.5L2.025 5.49727L0 3.24671L2.97037 2.60796L4.5 0Z" fill="#F2F2F2"/>
		<path d="M16.5 0L18.0296 2.60796L21 3.24671L18.975 5.49727L19.2812 8.5L16.5 7.28296L13.7188 8.5L14.025 5.49727L12 3.24671L14.9704 2.60796L16.5 0Z" fill="#F2F2F2"/>
		<path d="M28.5 0L30.0296 2.60796L33 3.24671L30.975 5.49727L31.2812 8.5L28.5 7.28296L25.7188 8.5L26.025 5.49727L24 3.24671L26.9704 2.60796L28.5 0Z" fill="#F2F2F2"/>
		<path d="M40.5 0L42.0296 2.60796L45 3.24671L42.975 5.49727L43.2812 8.5L40.5 7.28296L37.7188 8.5L38.025 5.49727L36 3.24671L38.9704 2.60796L40.5 0Z" fill="#F2F2F2"/>
		<path d="M52.5 0L54.0296 2.60796L57 3.24671L54.975 5.49727L55.2812 8.5L52.5 7.28296L49.7188 8.5L50.025 5.49727L48 3.24671L50.9704 2.60796L52.5 0Z" fill="#F2F2F2"/>
		</svg> (${hideOfloat(rating)})
		`;
	} else if (rating < 1) {
		return `<svg width="57" height="9" viewBox="0 0 57 9" fill="none" xmlns="http://www.w3.org/2000/svg">
		<path d="M4.5 0.500015L6.02963 3.10798L9 3.74673L6.975 5.99729L7.28115 9.00002L4.5 7.78298L1.71885 9.00002L2.025 5.99729L0 3.74673L2.97037 3.10798L4.5 0.500015Z" fill="#F2F2F2"/>
		<path d="M1.71885 9.00002L4.5 7.78298V0.500015L2.97037 3.10798L0 3.74673L2.025 5.99729L1.71885 9.00002Z" fill="#FFCB45"/>
		<path d="M16.5 0.500015L18.0296 3.10798L21 3.74673L18.975 5.99729L19.2812 9.00002L16.5 7.78298L13.7188 9.00002L14.025 5.99729L12 3.74673L14.9704 3.10798L16.5 0.500015Z" fill="#F2F2F2"/>
		<path d="M28.5 0.500015L30.0296 3.10798L33 3.74673L30.975 5.99729L31.2812 9.00002L28.5 7.78298L25.7188 9.00002L26.025 5.99729L24 3.74673L26.9704 3.10798L28.5 0.500015Z" fill="#F2F2F2"/>
		<path d="M40.5 0.500015L42.0296 3.10798L45 3.74673L42.975 5.99729L43.2812 9.00002L40.5 7.78298L37.7188 9.00002L38.025 5.99729L36 3.74673L38.9704 3.10798L40.5 0.500015Z" fill="#F2F2F2"/>
		<path d="M52.5 0.500015L54.0296 3.10798L57 3.74673L54.975 5.99729L55.2812 9.00002L52.5 7.78298L49.7188 9.00002L50.025 5.99729L48 3.74673L50.9704 3.10798L52.5 0.500015Z" fill="#F2F2F2"/>
		</svg> (${hideOfloat(rating)})
		`;
	} else if (rating == 1) {
		return `<svg width="57" height="9" viewBox="0 0 57 9" fill="none" xmlns="http://www.w3.org/2000/svg">
		<path d="M4.5 0L6.02963 2.60796L9 3.24671L6.975 5.49727L7.28115 8.5L4.5 7.28296L1.71885 8.5L2.025 5.49727L0 3.24671L2.97037 2.60796L4.5 0Z" fill="#FFCB45"/>
		<path d="M16.5 0L18.0296 2.60796L21 3.24671L18.975 5.49727L19.2812 8.5L16.5 7.28296L13.7188 8.5L14.025 5.49727L12 3.24671L14.9704 2.60796L16.5 0Z" fill="#F2F2F2"/>
		<path d="M28.5 0L30.0296 2.60796L33 3.24671L30.975 5.49727L31.2812 8.5L28.5 7.28296L25.7188 8.5L26.025 5.49727L24 3.24671L26.9704 2.60796L28.5 0Z" fill="#F2F2F2"/>
		<path d="M40.5 0L42.0296 2.60796L45 3.24671L42.975 5.49727L43.2812 8.5L40.5 7.28296L37.7188 8.5L38.025 5.49727L36 3.24671L38.9704 2.60796L40.5 0Z" fill="#F2F2F2"/>
		<path d="M52.5 0L54.0296 2.60796L57 3.24671L54.975 5.49727L55.2812 8.5L52.5 7.28296L49.7188 8.5L50.025 5.49727L48 3.24671L50.9704 2.60796L52.5 0Z" fill="#F2F2F2"/>
		</svg>
		  (${hideOfloat(rating)})`;
	} else if (rating < 2) {
		return `<svg width="57" height="9" viewBox="0 0 57 9" fill="none" xmlns="http://www.w3.org/2000/svg">
		<path d="M4.5 0.500008L6.02963 3.10797L9 3.74672L6.975 5.99728L7.28115 9.00001L4.5 7.78297L1.71885 9.00001L2.025 5.99728L0 3.74672L2.97037 3.10797L4.5 0.500008Z" fill="#FFCB45"/>
		<path d="M16.5 0.500008L18.0296 3.10797L21 3.74672L18.975 5.99728L19.2812 9.00001L16.5 7.78297L13.7188 9.00001L14.025 5.99728L12 3.74672L14.9704 3.10797L16.5 0.500008Z" fill="#F2F2F2"/>
		<path d="M13.7188 9.00001L16.5 7.78297V0.500008L14.9704 3.10797L12 3.74672L14.025 5.99728L13.7188 9.00001Z" fill="#FFCB45"/>
		<path d="M28.5 0.500008L30.0296 3.10797L33 3.74672L30.975 5.99728L31.2812 9.00001L28.5 7.78297L25.7188 9.00001L26.025 5.99728L24 3.74672L26.9704 3.10797L28.5 0.500008Z" fill="#F2F2F2"/>
		<path d="M40.5 0.500008L42.0296 3.10797L45 3.74672L42.975 5.99728L43.2812 9.00001L40.5 7.78297L37.7188 9.00001L38.025 5.99728L36 3.74672L38.9704 3.10797L40.5 0.500008Z" fill="#F2F2F2"/>
		<path d="M52.5 0.500008L54.0296 3.10797L57 3.74672L54.975 5.99728L55.2812 9.00001L52.5 7.78297L49.7188 9.00001L50.025 5.99728L48 3.74672L50.9704 3.10797L52.5 0.500008Z" fill="#F2F2F2"/>
		</svg>
		  (${hideOfloat(rating)})`;
	} else if (rating == 2) {
		return `<svg width="57" height="9" viewBox="0 0 57 9" fill="none" xmlns="http://www.w3.org/2000/svg">
		<path d="M4.5 7.62939e-06L6.02963 2.60797L9 3.24672L6.975 5.49728L7.28115 8.50001L4.5 7.28297L1.71885 8.50001L2.025 5.49728L0 3.24672L2.97037 2.60797L4.5 7.62939e-06Z" fill="#FFCB45"/>
		<path d="M16.5 7.62939e-06L18.0296 2.60797L21 3.24672L18.975 5.49728L19.2812 8.50001L16.5 7.28297L13.7188 8.50001L14.025 5.49728L12 3.24672L14.9704 2.60797L16.5 7.62939e-06Z" fill="#FFCB45"/>
		<path d="M28.5 7.62939e-06L30.0296 2.60797L33 3.24672L30.975 5.49728L31.2812 8.50001L28.5 7.28297L25.7188 8.50001L26.025 5.49728L24 3.24672L26.9704 2.60797L28.5 7.62939e-06Z" fill="#F2F2F2"/>
		<path d="M40.5 7.62939e-06L42.0296 2.60797L45 3.24672L42.975 5.49728L43.2812 8.50001L40.5 7.28297L37.7188 8.50001L38.025 5.49728L36 3.24672L38.9704 2.60797L40.5 7.62939e-06Z" fill="#F2F2F2"/>
		<path d="M52.5 7.62939e-06L54.0296 2.60797L57 3.24672L54.975 5.49728L55.2812 8.50001L52.5 7.28297L49.7188 8.50001L50.025 5.49728L48 3.24672L50.9704 2.60797L52.5 7.62939e-06Z" fill="#F2F2F2"/>
		</svg>
		  (${hideOfloat(rating)})`;
	} else if (rating < 3) {
		return `<svg width="57" height="9" viewBox="0 0 57 9" fill="none" xmlns="http://www.w3.org/2000/svg">
		<path d="M4.5 0.500008L6.02963 3.10797L9 3.74672L6.975 5.99728L7.28115 9.00001L4.5 7.78297L1.71885 9.00001L2.025 5.99728L0 3.74672L2.97037 3.10797L4.5 0.500008Z" fill="#FFCB45"/>
		<path d="M16.5 0.500008L18.0296 3.10797L21 3.74672L18.975 5.99728L19.2812 9.00001L16.5 7.78297L13.7188 9.00001L14.025 5.99728L12 3.74672L14.9704 3.10797L16.5 0.500008Z" fill="#FFCB45"/>
		<path d="M28.5 0.500008L30.0296 3.10797L33 3.74672L30.975 5.99728L31.2812 9.00001L28.5 7.78297L25.7188 9.00001L26.025 5.99728L24 3.74672L26.9704 3.10797L28.5 0.500008Z" fill="#F2F2F2"/>
		<path d="M25.7188 9.00001L28.5 7.78297V0.500008L26.9704 3.10797L24 3.74672L26.025 5.99728L25.7188 9.00001Z" fill="#FFCB45"/>
		<path d="M40.5 0.500008L42.0296 3.10797L45 3.74672L42.975 5.99728L43.2812 9.00001L40.5 7.78297L37.7188 9.00001L38.025 5.99728L36 3.74672L38.9704 3.10797L40.5 0.500008Z" fill="#F2F2F2"/>
		<path d="M52.5 0.500008L54.0296 3.10797L57 3.74672L54.975 5.99728L55.2812 9.00001L52.5 7.78297L49.7188 9.00001L50.025 5.99728L48 3.74672L50.9704 3.10797L52.5 0.500008Z" fill="#F2F2F2"/>
		</svg>
		  (${hideOfloat(rating)})`;
	} else if (rating == 3) {
		return `<svg width="57" height="9" viewBox="0 0 57 9" fill="none" xmlns="http://www.w3.org/2000/svg">
		<path d="M4.5 7.62939e-06L6.02963 2.60797L9 3.24672L6.975 5.49728L7.28115 8.50001L4.5 7.28297L1.71885 8.50001L2.025 5.49728L0 3.24672L2.97037 2.60797L4.5 7.62939e-06Z" fill="#FFCB45"/>
		<path d="M16.5 7.62939e-06L18.0296 2.60797L21 3.24672L18.975 5.49728L19.2812 8.50001L16.5 7.28297L13.7188 8.50001L14.025 5.49728L12 3.24672L14.9704 2.60797L16.5 7.62939e-06Z" fill="#FFCB45"/>
		<path d="M28.5 7.62939e-06L30.0296 2.60797L33 3.24672L30.975 5.49728L31.2812 8.50001L28.5 7.28297L25.7188 8.50001L26.025 5.49728L24 3.24672L26.9704 2.60797L28.5 7.62939e-06Z" fill="#FFCB45"/>
		<path d="M40.5 7.62939e-06L42.0296 2.60797L45 3.24672L42.975 5.49728L43.2812 8.50001L40.5 7.28297L37.7188 8.50001L38.025 5.49728L36 3.24672L38.9704 2.60797L40.5 7.62939e-06Z" fill="#F2F2F2"/>
		<path d="M52.5 7.62939e-06L54.0296 2.60797L57 3.24672L54.975 5.49728L55.2812 8.50001L52.5 7.28297L49.7188 8.50001L50.025 5.49728L48 3.24672L50.9704 2.60797L52.5 7.62939e-06Z" fill="#F2F2F2"/>
		</svg>
		  (${hideOfloat(rating)})`;
	} else if (rating < 4) {
		return `<svg width="57" height="9" viewBox="0 0 57 9" fill="none" xmlns="http://www.w3.org/2000/svg">
		<path d="M4.5 0.500008L6.02963 3.10797L9 3.74672L6.975 5.99728L7.28115 9.00001L4.5 7.78297L1.71885 9.00001L2.025 5.99728L0 3.74672L2.97037 3.10797L4.5 0.500008Z" fill="#FFCB45"/>
		<path d="M16.5 0.500008L18.0296 3.10797L21 3.74672L18.975 5.99728L19.2812 9.00001L16.5 7.78297L13.7188 9.00001L14.025 5.99728L12 3.74672L14.9704 3.10797L16.5 0.500008Z" fill="#FFCB45"/>
		<path d="M28.5 0.500008L30.0296 3.10797L33 3.74672L30.975 5.99728L31.2812 9.00001L28.5 7.78297L25.7188 9.00001L26.025 5.99728L24 3.74672L26.9704 3.10797L28.5 0.500008Z" fill="#FFCB45"/>
		<path d="M40.5 0.500008L42.0296 3.10797L45 3.74672L42.975 5.99728L43.2812 9.00001L40.5 7.78297L37.7188 9.00001L38.025 5.99728L36 3.74672L38.9704 3.10797L40.5 0.500008Z" fill="#F2F2F2"/>
		<path d="M37.7188 9.00001L40.5 7.78297V0.500008L38.9704 3.10797L36 3.74672L38.025 5.99728L37.7188 9.00001Z" fill="#FFCB45"/>
		<path d="M52.5 0.500008L54.0296 3.10797L57 3.74672L54.975 5.99728L55.2812 9.00001L52.5 7.78297L49.7188 9.00001L50.025 5.99728L48 3.74672L50.9704 3.10797L52.5 0.500008Z" fill="#F2F2F2"/>
		</svg>
		  (${hideOfloat(rating)})`;
	} else if (rating == 4) {
		return `<svg width="57" height="9" viewBox="0 0 57 9" fill="none" xmlns="http://www.w3.org/2000/svg">
		<path d="M4.5 0L6.02963 2.60796L9 3.24671L6.975 5.49727L7.28115 8.5L4.5 7.28296L1.71885 8.5L2.025 5.49727L0 3.24671L2.97037 2.60796L4.5 0Z" fill="#FFCB45"/>
		<path d="M16.5 0L18.0296 2.60796L21 3.24671L18.975 5.49727L19.2812 8.5L16.5 7.28296L13.7188 8.5L14.025 5.49727L12 3.24671L14.9704 2.60796L16.5 0Z" fill="#FFCB45"/>
		<path d="M28.5 0L30.0296 2.60796L33 3.24671L30.975 5.49727L31.2812 8.5L28.5 7.28296L25.7188 8.5L26.025 5.49727L24 3.24671L26.9704 2.60796L28.5 0Z" fill="#FFCB45"/>
		<path d="M40.5 0L42.0296 2.60796L45 3.24671L42.975 5.49727L43.2812 8.5L40.5 7.28296L37.7188 8.5L38.025 5.49727L36 3.24671L38.9704 2.60796L40.5 0Z" fill="#FFCB45"/>
		<path d="M52.5 0L54.0296 2.60796L57 3.24671L54.975 5.49727L55.2812 8.5L52.5 7.28296L49.7188 8.5L50.025 5.49727L48 3.24671L50.9704 2.60796L52.5 0Z" fill="#F2F2F2"/>
		</svg>
		  (${hideOfloat(rating)})`;
	} else if (rating < 5) {
		return `<svg width="57" height="9" viewBox="0 0 57 9" fill="none" xmlns="http://www.w3.org/2000/svg">
		<path d="M4.5 0.5L6.02963 3.10796L9 3.74671L6.975 5.99727L7.28115 9L4.5 7.78296L1.71885 9L2.025 5.99727L0 3.74671L2.97037 3.10796L4.5 0.5Z" fill="#FFCB45"/>
		<path d="M16.5 0.5L18.0296 3.10796L21 3.74671L18.975 5.99727L19.2812 9L16.5 7.78296L13.7188 9L14.025 5.99727L12 3.74671L14.9704 3.10796L16.5 0.5Z" fill="#FFCB45"/>
		<path d="M28.5 0.5L30.0296 3.10796L33 3.74671L30.975 5.99727L31.2812 9L28.5 7.78296L25.7188 9L26.025 5.99727L24 3.74671L26.9704 3.10796L28.5 0.5Z" fill="#FFCB45"/>
		<path d="M40.5 0.5L42.0296 3.10796L45 3.74671L42.975 5.99727L43.2812 9L40.5 7.78296L37.7188 9L38.025 5.99727L36 3.74671L38.9704 3.10796L40.5 0.5Z" fill="#FFCB45"/>
		<path d="M52.5 0.5L54.0296 3.10796L57 3.74671L54.975 5.99727L55.2812 9L52.5 7.78296L49.7188 9L50.025 5.99727L48 3.74671L50.9704 3.10796L52.5 0.5Z" fill="#F2F2F2"/>
		<path d="M49.7188 9L52.5 7.78296V0.5L50.9704 3.10796L48 3.74671L50.025 5.99727L49.7188 9Z" fill="#FFCB45"/>
		</svg>
		  (${hideOfloat(rating)})`;
	} else if (rating >= 5) {
		return ` <svg width="57" height="9" viewBox="0 0 57 9" fill="none" xmlns="http://www.w3.org/2000/svg">
		<path d="M4.50001 0L6.02965 2.60796L9.00001 3.24671L6.97501 5.49727L7.28117 8.5L4.50001 7.28296L1.71886 8.5L2.02502 5.49727L1.52439e-05 3.24671L2.97038 2.60796L4.50001 0Z" fill="#FFCB45"/>
		<path d="M16.5 0L18.0296 2.60796L21 3.24671L18.975 5.49727L19.2812 8.5L16.5 7.28296L13.7189 8.5L14.025 5.49727L12 3.24671L14.9704 2.60796L16.5 0Z" fill="#FFCB45"/>
		<path d="M28.5 0L30.0296 2.60796L33 3.24671L30.975 5.49727L31.2812 8.5L28.5 7.28296L25.7189 8.5L26.025 5.49727L24 3.24671L26.9704 2.60796L28.5 0Z" fill="#FFCB45"/>
		<path d="M40.5 0L42.0296 2.60796L45 3.24671L42.975 5.49727L43.2812 8.5L40.5 7.28296L37.7189 8.5L38.025 5.49727L36 3.24671L38.9704 2.60796L40.5 0Z" fill="#FFCB45"/>
		<path d="M52.5 0L54.0296 2.60796L57 3.24671L54.975 5.49727L55.2812 8.5L52.5 7.28296L49.7189 8.5L50.025 5.49727L48 3.24671L50.9704 2.60796L52.5 0Z" fill="#FFCB45"/>
		</svg>
		 (${hideOfloat(rating)})`;
	} else {
		return `<svg width="57" height="9" viewBox="0 0 57 9" fill="none" xmlns="http://www.w3.org/2000/svg">
		<path d="M4.5 0L6.02963 2.60796L9 3.24671L6.975 5.49727L7.28115 8.5L4.5 7.28296L1.71885 8.5L2.025 5.49727L0 3.24671L2.97037 2.60796L4.5 0Z" fill="#F2F2F2"/>
		<path d="M16.5 0L18.0296 2.60796L21 3.24671L18.975 5.49727L19.2812 8.5L16.5 7.28296L13.7188 8.5L14.025 5.49727L12 3.24671L14.9704 2.60796L16.5 0Z" fill="#F2F2F2"/>
		<path d="M28.5 0L30.0296 2.60796L33 3.24671L30.975 5.49727L31.2812 8.5L28.5 7.28296L25.7188 8.5L26.025 5.49727L24 3.24671L26.9704 2.60796L28.5 0Z" fill="#F2F2F2"/>
		<path d="M40.5 0L42.0296 2.60796L45 3.24671L42.975 5.49727L43.2812 8.5L40.5 7.28296L37.7188 8.5L38.025 5.49727L36 3.24671L38.9704 2.60796L40.5 0Z" fill="#F2F2F2"/>
		<path d="M52.5 0L54.0296 2.60796L57 3.24671L54.975 5.49727L55.2812 8.5L52.5 7.28296L49.7188 8.5L50.025 5.49727L48 3.24671L50.9704 2.60796L52.5 0Z" fill="#F2F2F2"/>
		</svg> (${hideOfloat(rating)})`;
	}

}

function hideOfloat(n) {
	return Number(n).toFixed(2).replace(/(\.0+|0+)$/, '');
}

function setBtnLoading(element, text, status = true) {
	const el = $(element);
	if (status) {
		el.attr("disabled", "");
		el.html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> ${text}`);
	} else {
		el.removeAttr("disabled");
		el.html(text);
	}
}

function ajax_select_kode(btn = { id: '', pretext: '', text: '' }, select, url, data = null, modal = false, initial_select = false, selected = '', method = 'post') {
	if (btn == false) {
		setBtnLoading(btn.id, btn.pretext);
	}
	$.ajax({
		method: 'post',
		url: url,
		data: data
	}).done((data) => {
		const init_select = initial_select != false ? `<option value="">${initial_select}</option>` : '';
		// console.log(init_select);
		$(select).html('');
		let html = init_select;
		data.forEach(e => {
			const select = e.id == selected ? 'selected' : '';
			html += `<option value="${e.id}" ${select}>${e.kode} - ${e.text}</option>`;
		});
		$(select).html(html);
		$(modal).modal('toggle');
	}).fail(($xhr) => {
		Toast.fire({
			icon: 'error',
			title: 'Gagal mendapatkan data.'
		})
	}).always(() => {
		if (btn == false) {
			setBtnLoading(btn.id, btn.text, false);
		}
	})
}

function ajax_select_by(btn = { id: '', pretext: '', text: '' }, select, url, data = null, modal = false, initial_select = false, selected = '', method = 'post') {
	if (btn == false) {
		setBtnLoading(btn.id, btn.pretext);
	}
	$.ajax({
		method: 'post',
		url: url,
		data: data
	}).done((data) => {
		const init_select = initial_select != false ? `<option value="">${initial_select}</option>` : '';
		// console.log(init_select);
		$(select).html('');
		let html = init_select;
		data.forEach(e => {
			const select = e.id == selected ? 'selected' : '';
			if(e.id == 1){
				html += `<option value="${e.id}" ${select}>Super Admin</option>`;
			}else{
				html += `<option value="${e.id}" ${select}>${e.text}</option>`;
			}
		});
		$(select).html(html);
		$(modal).modal('toggle');
	}).fail(($xhr) => {
		Toast.fire({
			icon: 'error',
			title: 'Gagal mendapatkan data.'
		})
	}).always(() => {
		if (btn == false) {
			setBtnLoading(btn.id, btn.text, false);
		}
	})
}

function ajax_select(btn = { id: '', pretext: '', text: '' }, select, url, data = null, modal = false, initial_select = false, selected = '', method = 'post') {
	if (btn == false) {
		setBtnLoading(btn.id, btn.pretext);
	}
	$.ajax({
		method: 'post',
		url: url,
		data: data
	}).done((data) => {
		const init_select = initial_select != false ? `<option value="">${initial_select}</option>` : '';
		// console.log(init_select);
		$(select).html('');
		let html = init_select;
		data.forEach(e => {
			const select = e.id == selected ? 'selected' : '';
			html += `<option value="${e.id}" ${select}>${e.text}</option>`;
		});
		$(select).html(html);
		$(modal).modal('toggle');
	}).fail(($xhr) => {
		Toast.fire({
			icon: 'error',
			title: 'Gagal mendapatkan data.'
		})
	}).always(() => {
		if (btn == false) {
			setBtnLoading(btn.id, btn.text, false);
		}
	})
}

function format_rupiah(angka, format = 2, prefix) {
	angka = angka != "" ? angka : 0;
	angka = parseFloat(angka);
	angka_ = angka.toString().split('.');
	if (format) {
		if (angka_[1]) {
			const len = String(angka_[1]).length;

			angka = angka.toFixed(format > len ? len : format);
		}
	}
	const minus = angka < 0 ? "-" : "";
	angka = angka.toString().split('.');
	let suffix = angka[1] ? angka[1] : '';

	angka = angka[0];
	if (angka) {
		let number_string = angka.toString().replace(/[^,\d]/g, '').toString(),
			split = number_string.split(','),
			sisa = split[0].length % 3,
			rupiah = split[0].substr(0, sisa),
			ribuan = split[0].substr(sisa).match(/\d{3}/gi)

		// tambahkan titik jika yang di input sudah menjadi angka ribuan
		if (ribuan) {
			separator = sisa ? '.' : ''
			rupiah += separator + ribuan.join('.')
		}

		rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah

		// return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '')
		const result = prefix == undefined ? rupiah : (rupiah ? prefix + rupiah : '');
		return minus + result + (suffix != '' ? ',' + suffix : '');
	}
	else {
		return 0
	}
}