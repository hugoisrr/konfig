<?php

use Illuminate\Database\Seeder;

class AccessReasonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // You are subscribed to this therapy session
        $accessReason = \App\Http\Controllers\AccessReasonController::store(15);
        \App\Http\Controllers\TranslationAccessReasonController::storeAccessReasonTranslation(
            'You are subscribed to this therapy session.',
            $accessReason,
            \App\Http\Controllers\CourseController::getLanguage('en'));
        \App\Http\Controllers\TranslationAccessReasonController::storeAccessReasonTranslation(
            'Du hast diese Therapiesitzung abonniert.',
            $accessReason,
            \App\Http\Controllers\CourseController::getLanguage('de'));
        \App\Http\Controllers\TranslationAccessReasonController::storeAccessReasonTranslation(
            'Vous êtes abonné à cette séance de thérapie.',
            $accessReason,
            \App\Http\Controllers\CourseController::getLanguage('fr'));
        \App\Http\Controllers\TranslationAccessReasonController::storeAccessReasonTranslation(
            'Вы подписаны на этот сеанс терапии.',
            $accessReason,
            \App\Http\Controllers\CourseController::getLanguage('ru'));
        \App\Http\Controllers\TranslationAccessReasonController::storeAccessReasonTranslation(
            'Estás suscrito a esta sesión de terapia.',
            $accessReason,
            \App\Http\Controllers\CourseController::getLanguage('es'));
        \App\Http\Controllers\TranslationAccessReasonController::storeAccessReasonTranslation(
            'Você está inscrito nesta sessão de terapia.',
            $accessReason,
            \App\Http\Controllers\CourseController::getLanguage('pt'));

        // This is a trial session, free of charge
        $accessReason = \App\Http\Controllers\AccessReasonController::store(0);
        \App\Http\Controllers\TranslationAccessReasonController::storeAccessReasonTranslation(
            'This is a trial session, free of charge.',
            $accessReason,
            \App\Http\Controllers\CourseController::getLanguage('en'));
        \App\Http\Controllers\TranslationAccessReasonController::storeAccessReasonTranslation(
            'Dies ist eine kontenlose Probesitzung.',
            $accessReason,
            \App\Http\Controllers\CourseController::getLanguage('de'));
        \App\Http\Controllers\TranslationAccessReasonController::storeAccessReasonTranslation(
            "Ceci est une session d'essai gratuite.",
            $accessReason,
            \App\Http\Controllers\CourseController::getLanguage('fr'));
        \App\Http\Controllers\TranslationAccessReasonController::storeAccessReasonTranslation(
            'Это бесплатная пробная сессия.',
            $accessReason,
            \App\Http\Controllers\CourseController::getLanguage('ru'));
        \App\Http\Controllers\TranslationAccessReasonController::storeAccessReasonTranslation(
            'Esta es una sesión de prueba, gratuita.',
            $accessReason,
            \App\Http\Controllers\CourseController::getLanguage('es'));
        \App\Http\Controllers\TranslationAccessReasonController::storeAccessReasonTranslation(
            'Esta é uma sessão de teste gratuita.',
            $accessReason,
            \App\Http\Controllers\CourseController::getLanguage('pt'));

        // Temporal Promotion
        $accessReason = \App\Http\Controllers\AccessReasonController::store(50);
        \App\Http\Controllers\TranslationAccessReasonController::storeAccessReasonTranslation(
            'Temporal promotion.',
            $accessReason,
            \App\Http\Controllers\CourseController::getLanguage('en'));
        \App\Http\Controllers\TranslationAccessReasonController::storeAccessReasonTranslation(
            'Temporäre Promotion.',
            $accessReason,
            \App\Http\Controllers\CourseController::getLanguage('de'));
        \App\Http\Controllers\TranslationAccessReasonController::storeAccessReasonTranslation(
            "Promotion temporelle.",
            $accessReason,
            \App\Http\Controllers\CourseController::getLanguage('fr'));
        \App\Http\Controllers\TranslationAccessReasonController::storeAccessReasonTranslation(
            'Временное продвижение.',
            $accessReason,
            \App\Http\Controllers\CourseController::getLanguage('ru'));
        \App\Http\Controllers\TranslationAccessReasonController::storeAccessReasonTranslation(
            'Promoción temporal.',
            $accessReason,
            \App\Http\Controllers\CourseController::getLanguage('es'));
        \App\Http\Controllers\TranslationAccessReasonController::storeAccessReasonTranslation(
            'Promoção temporal.',
            $accessReason,
            \App\Http\Controllers\CourseController::getLanguage('pt'));
    }

}
