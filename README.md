## About Password Manager

The Password Manager is an application for managing passwords with the implementation of user sessions and password encryptionel.

## User sessions

User sessions are implemented through a token issued to the user upon authorization containing the session token and payload.

The payload is encrypted using the RSA cryptographic algorithm.

```session_token.payload```

## Password encryption

User passwords are encrypted using the RSA cryptographic algorithm.
