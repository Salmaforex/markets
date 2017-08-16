<script src="<?=base_url();?>media/plugins/ckeditor/ckeditor.js"></script>
<link href="<?=base_url();?>media/js/bootstrap.css" rel="stylesheet">
<link href="<?=base_url();?>media/plugins/ckeditor/sample.css" rel="stylesheet">
<form action="<?=current_url();?>" novalidate="novalidate" name="frm" id0="frm" id="frmLiveAccount" method="POST"  class="form-horizontal" role="form">
Subject: <input name='subject' value='[SALMAFOREX] Informasi Libur' />
<br/>Tujuan:
<input type='radio' name='to' value='1' checked /><input type='to_email' />
<input type='radio' name='to' value='all'/> Semua
<hr/>
<?php
$def='
<h3>Pengumuman Trading</h3>
<b>Klien yang terhormat</b>,<br/>
bersamaan dengan email ini kami infokan
<div style="background:rgba(219, 228, 231, 0.51); padding:5px; ">&nbsp;<br/><table style="width:100%" border="1">
	<tbody>
		<tr>
			<th>Tanggal</th>
			<th>Keterangan</th>
		</tr>
		<tr>
			<td>20-01-2016</td>
			<td>{info}</td>
		</tr>
		<tr>
			<td>20-01-2016</td>
			<td>{info}</td>
		</tr>
	</tbody>
</table></div>
Bila anda memiliki pertanyaan, silakan menghubungi layanan kontak kami,
';?>
<textarea name='message' id="editor1"><?=htmlentities($def);?></textarea>
<button type="submit" class="btn btn-default"  >
       Kirim
</button>
</form>
		<script>

			// This call can be placed at any point after the
			// <textarea>, or inside a <head><script> in a
			// window.onload event handler.

			// Replace the <textarea id="editor"> with an CKEditor
			// instance, using default configurations.

			CKEDITOR.replace( 'editor1' );

		</script>