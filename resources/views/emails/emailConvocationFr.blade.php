<td style="padding: 15px; font-family: sans-serif; font-size: 15px; line-height: 140%; color: #555555;">    
    <p>Cher / Chère {{ $staff_name }},</p>
    <p style="text-align: justify">
        Nous avons le plaisir de vous informer que vous êtes inscrit(e) avec succès à un cours de langue du <strong>{{ $term_fr }}</strong>. Voici ci-dessous les informations relatives à votre cours :
    </p>
    
    <p>
        <h3><strong>{{ $course_name_fr }}</strong></h3>

        Horaire : <strong>{{$schedule}}</strong> 
        <br> 
        Professeur : <strong>{{ $teacher }}</strong> ({{ $teacher_email }})
        <br> 
        <br> 
        @foreach($classrooms as $classroom)
            @if(!empty($classroom->Te_Mon_Room))
            <p>Salle du lundi : <strong>{{ $classroom->roomsMon->Rl_Room }}</strong></p>
            {{-- <p>horaire lundi : <strong>{{ date('H:i', strtotime($classroom->Te_Mon_BTime)) }} - {{ date('H:i', strtotime($classroom->Te_Mon_ETime ))}}</strong></p> --}}
            @endif
            @if(!empty($classroom->Te_Tue_Room))
            <p>Salle du mardi : <strong>{{ $classroom->roomsTue->Rl_Room }}</strong></p>
            {{-- <p>horaire mardi : <strong>{{ date('H:i', strtotime($classroom->Te_Tue_BTime)) }} - {{ date('H:i', strtotime($classroom->Te_Tue_ETime)) }}</strong></p> --}}
            @endif
            @if(!empty($classroom->Te_Wed_Room))
            <p>Salle du mercredi : <strong>{{ $classroom->roomsWed->Rl_Room }}</strong></p>
            {{-- <p>horaire mercredi : <strong>{{ date('H:i', strtotime($classroom->Te_Wed_BTime ))}} - {{ date('H:i', strtotime($classroom->Te_Wed_ETime)) }}</strong></p> --}}
            @endif
            @if(!empty($classroom->Te_Thu_Room))
            <p>Salle du jeudi : <strong>{{ $classroom->roomsThu->Rl_Room }}</strong></p>
            {{-- <p>horaire jeudi : <strong>{{ date('H:i', strtotime($classroom->Te_Thu_BTime)) }} - {{ date('H:i', strtotime($classroom->Te_Thu_ETime ))}}</strong></p> --}}
            @endif
            @if(!empty($classroom->Te_Fri_Room))
            <p>Salle du vendredi : <strong>{{ $classroom->roomsFri->Rl_Room }}</strong></p>
            {{-- <p>horaire vendredi : <strong>{{ date('H:i', strtotime($classroom->Te_Fri_BTime ))}} - {{ date('H:i', strtotime($classroom->Te_Fri_ETime)) }}</strong></p> --}}
            @endif
            @if(!empty($classroom->Te_Sat_Room))
            <p>Salle du samedi : <strong>{{ $classroom->roomsSat->Rl_Room }}</strong></p>
            {{-- <p>horaire samedi : <strong>{{ date('H:i', strtotime($classroom->Te_Sat_BTime ))}} - {{ date('H:i', strtotime($classroom->Te_Sat_ETime)) }}</strong></p> --}}
            @endif
        @endforeach
    </p>

    <ul>
        <li style="text-align: justify">Une <strong>semaine d'orientation</strong>, organisée une semaine avant le début des cours, vous permettra de vous familiariser avec vos outils d'apprentissage, y compris la plateforme d'apprentissage des langues, et de rencontrer votre professeur et votre groupe.
        </li>    
        <br />
        <li style="text-align: justify">
        Pendant la semaine d'orientation, votre professeur vous enverra par e-mail des instructions supplémentaires sur la session de bienvenue (en ligne) de 45 minutes, le lien vers votre matériel de cours et vos activités en autonomie sur la plateforme d'apprentissage des langues de CLM. 
        </li>
        <br />
        <li style="text-align: justify">Certains cours nécessitent l’achat de matériel. Veuillez consulter <a href="https://learning.unog.ch/fr/node/1443">la liste du matériel </a> nécessaire avant le début de votre cours.</li>
    </ul>
    <h4><strong><u>Informations importantes pour les cours EN LIGNE</u></strong></h4>
    <p style="text-align: justify">Veuillez prendre connaissance du matériel requis :</p>
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="max-width: 600px;">
        <tr>
            <td style="padding: 20px 0; text-align: center">
                <img src="https://ltponlinedev.unog.ch/img/online_equip_icons_fr.png" width="450" alt="CLM Language Training" border="0" style="height: auto; background: #dddddd; font-family: sans-serif; font-size: 15px; line-height: 140%; color: #555555;">
            </td>
        </tr>
    </table>
    <p style="text-align: justify">Nous utiliserons Microsoft Teams comme outil de téléconférence. Les sessions auront lieu au moment indiqué ci-dessus. Les professeurs vous enverront de plus amples informations sur la manière de participer à votre session pendant la semaine d’orientation.</p>
    
    <h4><strong><u>Informations importantes pour les cours EN PRÉSENTIEL</u></strong></h4>
    <p style="text-align: justify">En raison de la crise de liquidité, l'Annexe Bocage est temporairement fermée. Les cours de langue en présentiel se tiendront dans le bâtiment H, à partir du lundi 29 avril 2024.</p>

    <p style="text-align: justify">- <a href="https://learning.unog.ch/sites/default/files/ContainerEn/LTP/Admin/LTP%20Location.pdf">Plan du Palais des Nations, Bâtiment H (localisation des salles de cours)</a> <br /> - <a href="https://learning.unog.ch/fr/node/1446">Carte d'accès au terrain et/ou badge de stationnement pour les véhicules</a></p>

    <h4><strong><u>Vous ne pouvez pas être présent(e)s la première semaine de cours ? </u></strong></h4>
    <p style="text-align: justify">Veuillez en informer à l’avance votre professeur(e) par email. Les participant(e)s absent(e)s la première semaine de cours peuvent se voir désinscrit(e)s du cours et leur place donnée aux participant(e)s de la liste d’attente.</p>

    <h4><strong><u>Vous devez annuler votre inscription ? </u></strong></h4>
    <p style="text-align: justify">Vous devez le faire avant le {{ $cancel_date_limit_string }} à 23h59. Aucun frais de cours ne sera remboursé après cette date. 
        <ul>
            <li style="text-align: justify">
                Pour annuler, <a href="https://ltponlinedev.unog.ch/previous-submitted">cliquez ICI</a>, sélectionnez le trimestre approprié, puis cliquez sur le bouton rouge « Cancel Enrolment » (<a href="https://learning.unog.ch/fr/node/1301#position7">plus d’informations</a>).
            </li>
            <li style="text-align: justify">
                Pour toute information sur le remboursement, <a href="https://learning.unog.ch/fr/node/1301#position5">cliquez ICI</a>.
            </li>
        </ul>
    </p>
    
    <p style="text-align: justify"><em>En cas de retard dans l’annulation, la raison technique ne sera pas considérée comme valable pour le remboursement, ni pour une non-facturation de votre organisation. Merci de votre compréhension.</em></p>

    <p style="text-align: justify">Si vous avez des questions, merci de consulter nos <a href="hhttps://learning.unog.ch/fr/node/1301#position8">FAQs</a>.</p>
    {{-- <ol>
        <li>
            <strong>Pour les cours annoncés en ligne :</strong>
        </li>
        <p style="text-align: justify">
        <b>Microsoft Teams</b> <br>
        Nous utiliserons Microsoft Teams comme outil de téléconférence. Les sessions auront lieu au moment indiqué ci-dessus. Les professeurs vous enverront de plus amples informations sur la manière de participer à votre session avant le début du trimestre.
        </p>
        
        <p style="text-align: justify">
        <b>Exigences informatiques</b> <br>
        <ul>
            <li style="text-align: justify">
                Assurez-vous que vous avez une caméra, des écouteurs et un microphone.
            </li>
            <li style="text-align: justify">
                Testez la connexion avant le cours. 
            </li>
            <li style="text-align: justify">
                Contactez le département informatique de votre organisation si vous avez un problème.
            </li>
            <li style="text-align: justify">
                Lorsque vous assistez au cours, fermez toutes les autres applications et tous les autres dossiers. Cela vous permettra de vous concentrer sur le contenu du cours comme si vous suiviez un cours en face à face et de ne pas surcharger la bande passante.
            </li>
        </ul>
        </p>

        <li>
            <strong>Pour tous les cours, en ligne et en présentiel :</strong>
        </li>
        <p style="text-align: justify">
        <b><a href="https://moodle.unog.ch/unog/login/index.php">Moodle</a></b> <br>
        <ul>
            <li style="text-align: justify">
                <u>Lorsque vous en serez informé(e) par votre professeur(e)</u>, vous trouverez votre matériel d'apprentissage et vos activités en autonomie sur la plateforme d'apprentissage Moodle du CFM : <a href="https://moodle.unog.ch/unog/login/index.php">https://moodle.unog.ch/unog/login/index.php</a>. Avant de commencer votre cours, assurez-vous de vous connecter à votre cours pour vous familiariser avec le contenu et accéder au matériel de formation.
            </li>
            <li style="text-align: justify">
                Si vous n'avez pas encore votre accès à Moodle, veuillez vous connecter avec les identifiants suivants :
                <br />- Nom d’utilisateur : la première partie avant le @ de votre adresse email (ex. “psmith” pour psmith@un.org) 
                <br />- Mot de passe par défaut : Welcome2U_2022# (vous devrez le modifier lors de la première connexion)
            </li>
        </ul>
            </p>
    </ol> --}}
    <br /> 
    <p style="text-align: justify">
       Le Programme de formation linguistique vous souhaite une expérience riche en apprentissage.
    </p>
    <br>
    
    <p style="text-align: justify">
        Pour en savoir plus, rendez-vous sur notre site Internet : <a href="https://learning.unog.ch/fr/language-index">https://learning.unog.ch/fr/language-index</a>
    </p>
</td>