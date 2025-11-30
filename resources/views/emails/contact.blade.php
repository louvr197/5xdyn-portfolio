<x-mail::message>
# Nouveau message de contact

Vous avez reçu un nouveau message via votre portfolio.

## Informations de l'expéditeur

**Nom :** {{ $senderName }}
**Email :** {{ $senderEmail }}
**Sujet :** {{ $subject }}

## Message

{!! $messageContent !!}

---

Pour répondre à ce message, vous pouvez directement répondre à cet email.

Cordialement,
Votre portfolio
</x-mail::message>
