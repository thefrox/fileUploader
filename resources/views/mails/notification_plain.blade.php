<p><u>Voici votre image :</u></p>
 
<div>
<p><b>Dimensions :</b>&nbsp;{{ $notification->width }} * {{ $notification->height }}</p>
<p><b>Poids du fichier :</b>&nbsp;{{ $notification->weight }} Mo</p>
<p><b>Lien vers l'image :</b>&nbsp;<a href="{{asset('uploads')}}/{{ $notification->file }}">{{asset('uploads')}}/{{ $notification->file }}</a></p>
</div>
 
