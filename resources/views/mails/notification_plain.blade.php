<p><u>Voici votre image :</u></p>
 
<div>
<p><b>Nom de l'image :</b>&nbsp;{{ $notification->name }}</p>
<p><b>Dimensions :</b>&nbsp;{{ $notification->width }} * {{ $notification->height }}</p>
<p><b>Poids du fichier :</b>&nbsp;{{ $notification->weight }} Kb</p>
<p><b>Lien vers l'image :</b>&nbsp;<a href="{{url('uploads')}}/{{ $notification->url }}">{{url('uploads')}}/{{ $notification->url }}</a></p>
</div>
 
