@extends('layouts.app')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<link rel="stylesheet" href="{{ asset('css/contact.css') }}">
<link rel="stylesheet" href="{{ asset('css/styleIndex.css') }}"> 

@section('content')

<section class="contact-section container">
    <div class="row">
        <div class="col-12">
          <div class="map-container rounded mb-4 shadow-sm">
             <iframe src="https://www.google.com/maps/embed?pb=!1m16!1m12!1m3!1d253342.8877837763!2d1.8826012608468972!3d7.1856393255345745!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!2m1!1sLes%20couurs%20sonou%20Bohicon!5e0!3m2!1sfr!2sbj!4v1764805289006!5m2!1sfr!2sbj"
              width="100%" height="350" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
            </iframe>
          </div>

          <div class="">
            <h1 class="pt-3 mb-0 section-title text-center" >Contactez nos Experts</h1>
            <p class="mb-0 text-center">Notre équipe d'experts vous mets en réseau avec plus de clients internationaux</p>
          </div>
          <div class="alert alert-success my-2">
            <p class="fw-semibold text-center">Veuillez soumettre votre demande via le formulaire ci-dessous si vous désirez devenir vendeur, livreur ou toute autre prestataire...</p>
          </div>
        </div>



        <div class="col-md-5 animate__animated animate__fadeInLeft">
            <div class="contact-card legal-info">
                <div class="d-flex align-items-center mb-2">
                    <i class="bi bi-telephone me-2 text-primary"></i>
                    <h6 class="mb-0 text-primary">Téléphone</h6>
                </div>
                <p>(+229 01 96 45 67 89)<br>(+229 01 96 45 67 89)</p>
            </div>

            <div class="contact-card legal-info">
                <div class="d-flex align-items-center mb-2">
                    <i class="bi bi-envelope me-2 text-primary"></i>
                    <h6 class="mb-0 text-primary">Email</h6>
                </div>
                <p>contact@shopeasy.com</p>
            </div>

            <div class="contact-card legal-info">
                <div class="d-flex align-items-center mb-2">
                    <i class="bi bi-geo-alt me-2 text-primary"></i>
                    <h6 class="mb-0 text-primary">Adresse</h6>
                </div>
                <p>Bohicon, BÉNIN</p>
            </div>

            <div class="contact-card legal-info">
                <div class="d-flex align-items-center mb-2">
                    <i class="bi bi-clock me-2 text-primary"></i>
                    <h6 class="mb-0 text-primary">Horaires</h6>
                </div>
                <p>Opérationnel H24</p>
            </div>

            <div class="contact-card legal-info">
                <div class="d-flex align-items-center mb-2">
                    <i class="bi bi-file-earmark-text me-2 text-primary"></i>
                    <h6 class="mb-0 text-primary">Informations légales</h6>
                </div>
                <p>RCC : RB/SHP/112233<br>IFU : 12322508482554</p>
            </div>
        </div>

        <!-- Carte et formulaire -->
        <div class="col-md-7 animate__animated animate__fadeInRight">
            <div class="contact-card legal-info">
                <h5><i class="bi bi-chat-dots me-2"></i> Envoyez-nous un message</h5>
                <p>Décrivez votre projet en détail. Réponse sous 24h ouvrées.</p>
                <form id="contactForm" action="{{ route('contact.store') }}" method="POST">
                    @csrf
                    <div class="row mb-3">
                        <div class="col">
                            <label for="nom">Nom *</label>
                            <input type="text" class="form-control" name="nom" required>
                        </div>
                        <div class="col">
                            <label for="prenom">Prénom *</label>
                            <input type="text" class="form-control" name="prenom" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label for="email">Email *</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="col">
                            <label for="tel">Téléphone</label>
                            <input type="text" class="form-control" name="tel">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="prestation">Type de prestation</label>
                        <select class="form-control" name="prestation">
                            <option selected disabled>Sélectionnez le type de votre projet</option>
                            <option>Devenir vendeur/propriétaire chez ShopEasy</option>
                            <option>Devenir livreur chez ShopEasy</option>
                            <option>Autre demande</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="message">Votre message *</label>
                        <textarea name="message" class="form-control" rows="5" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-send me-2"></i> Envoyer le message
                    </button>
                </form>
                
            </div>
        </div>
    </div>
</section>

@endsection
