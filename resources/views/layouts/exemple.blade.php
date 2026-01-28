<script>
            $data = [
            'mode' => [
                "Vêtements"   => ["Hommes","Femmes","Enfants"],
                "Chaussures"  => ["Hommes","Femmes","Sport"],
                "Accessoires" => ["Sacs","Bijoux","Montres"],
            ],
            'hightech' => [
                "Téléphones"   => ["Android","iPhone","Accessoires"],
                "Ordinateurs"  => ["Portable","Bureau","Accessoires"],
                "Audio / Vidéo"=> ["Écouteurs","Enceintes","Télévisions"],
            ],
            'maison' => [
                "Cuisine"     => ["Ustensiles","Électroménager","Vaisselle"],
                "Décoration"  => ["Maison","Bureau","Luminaires"],
                "Meubles"     => ["Salon","Chambre","Bureau"],
            ],
            'sport' => [
                "Sport"   => ["Fitness","Football","Accessoires"],
                "Loisirs" => ["Jeux","Musique","Plein air"],
            ],
        ];
</script>


<!-- PRODUITS (MEGA MENU) -->
<li class="dropdown">
    <a class="nav-link dropdown-toggle">Produits</a>
    <div class="mega-menu">
        <div class="mega-row">

            <!-- MODE -->
            <div class="mega-col">
                <h6>Mode</h6>
                <strong style="color: var(--primary-color);">Vêtements</strong>
                <a href="/catalogue?categorie=vetements&type=vente&etat=hommes">Hommes</a>
                <a href="/catalogue?categorie=vetements&type=vente&etat=femmes">Femmes</a>
                <a href="/catalogue?categorie=vetements&type=vente&etat=enfants">Enfants</a>
                <br>
                <strong style="color: var(--primary-color);">Chaussures</strong>
                <a href="/catalogue?categorie=chaussures&type=vente&etat=hommes">Hommes</a>
                <a href="/catalogue?categorie=chaussures&type=vente&etat=femmes">Femmes</a>
                <a href="/catalogue?categorie=chaussures&type=vente&etat=sport">Sport</a>
                <br>
                <strong style="color: var(--primary-color);">Accessoires</strong>
                <a href="/catalogue?categorie=accessoires&type=vente&etat=sacs">Sacs</a>
                <a href="/catalogue?categorie=accessoires&type=vente&etat=bijoux">Bijoux</a>
                <a href="/catalogue?categorie=accessoires&type=vente&etat=montres">Montres</a>
            </div>

            <!-- HIGH TECH -->
            <div class="mega-col">
                <h6>High-Tech</h6>
                <strong style="color: var(--primary-color);">Téléphones</strong>
                <a href="/catalogue?categorie=telephones&type=vente&etat=android">Android</a>
                <a href="/catalogue?categorie=telephones&type=vente&etat=iphone">iPhone</a>
                <a href="/catalogue?categorie=telephones&type=vente&etat=accessoires">Accessoires</a>
                <br>
                <strong style="color: var(--primary-color);">Ordinateurs</strong>
                <a href="/catalogue?categorie=pc&type=vente&etat=portable">Portables</a>
                <a href="/catalogue?categorie=pc&type=vente&etat=bureau">Bureau</a>
                <a href="/catalogue?categorie=pc&type=vente&etat=accessoires">Accessoires</a>
                <br>
                <strong style="color: var(--primary-color);">Audio / Vidéo</strong>
                <a href="/catalogue?categorie=audio&type=vente&etat=ecouteurs">Écouteurs</a>
                <a href="/catalogue?categorie=audio&type=vente&etat=enceintes">Enceintes</a>
                <a href="/catalogue?categorie=audio&type=vente&etat=televisions">Télévisions</a>
            </div>

            <!-- MAISON & LIFESTYLE -->
            <div class="mega-col">
                <h6>Maison & Lifestyle</h6>
                <strong style="color: var(--primary-color);">Cuisine</strong>
                <a href="/catalogue?categorie=cuisine&type=vente&etat=ustensiles">Ustensiles</a>
                <a href="/catalogue?categorie=cuisine&type=vente&etat=electromenager">Électroménager</a>
                <a href="/catalogue?categorie=cuisine&type=vente&etat=vaisselle">Vaisselle</a>
                <br>
                <strong style="color: var(--primary-color);">Décoration</strong>
                <a href="/catalogue?categorie=decoration&type=vente&etat=maison">Maison</a>
                <a href="/catalogue?categorie=decoration&type=vente&etat=bureau">Bureau</a>
                <a href="/catalogue?categorie=decoration&type=vente&etat=luminaire">Luminaires</a>
                <br>
                <strong style="color: var(--primary-color);">Meubles</strong>
                <a href="/catalogue?categorie=meubles&type=vente&etat=salon">Salon</a>
                <a href="/catalogue?categorie=meubles&type=vente&etat=chambre">Chambre</a>
                <a href="/catalogue?categorie=meubles&type=vente&etat=bureau">Bureau</a>
            </div>

            <!-- SPORT & LOISIRS -->
            <div class="mega-col">
                <h6>Sport & Loisirs</h6>
                <strong style="color: var(--primary-color);">Sport</strong>
                <a href="/catalogue?categorie=sport&type=vente&etat=fitness">Fitness</a>
                <a href="/catalogue?categorie=sport&type=vente&etat=football">Football</a>
                <a href="/catalogue?categorie=sport&type=vente&etat=accessoires">Accessoires</a>
                <br>
                <strong style="color: var(--primary-color);">Loisirs</strong>
                <a href="/catalogue?categorie=loisirs&type=vente&etat=jeux">Jeux</a>
                <a href="/catalogue?categorie=loisirs&type=vente&etat=musique">Musique</a>
                <a href="/catalogue?categorie=loisirs&type=vente&etat=plein-air">Plein air</a>
            </div>

        </div>
    </div>
</li>