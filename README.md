📘 ExpenseManager – Laravel Microservices Authentication (End-to-End Journey)

This repository documents the complete, real-world journey of building a JWT-based authentication system using Laravel microservices, designed to support React web and Ionic mobile applications.

This README is written for:

Future self reference

Team onboarding

Senior-level interviews

It explains what was done, in what order, why decisions were taken, and what problems were solved.

🧭 0. Goal and Requirements
Goal

Build a production-grade authentication system with:

Laravel microservices

Stateless JWT authentication

Support for Web (React) and Mobile (Ionic)

Splash login for mobile apps

Ability to block or delete users centrally

Core constraints

No shared database between services

Auth logic must be stateless

Clean separation of responsibilities

🧱 1. Initial Architecture Planning

At the beginning, three services were planned and created together, even though development happened in stages:

user-service → owns user data

auth-service → handles authentication and JWT

api-gateway → planned for future routing

High-level idea:

Client (React / Ionic)
        |
        v
   API Gateway (future)
        |
        v
   Auth-Service ─────▶ User-Service

🧑 2. User-Service Creation (First Actual Implementation)

User-service was implemented first, because:

Authentication depends on user data

User schema must be stable before auth logic

Command
composer create-project laravel/laravel user-service

Run service
php artisan serve --port=8002

🗄️ 3. User Model and Database Design
Design decisions

Internal numeric primary key for performance

UUID (guid) as public identifier

Soft delete using flag instead of physical delete

Status-based access (active, blocked)

Model

Extended Laravel’s default User model

Added business fields:

guid

first_name, last_name

email, phone

role

status

is_deleted

Why this matters

Prevents user ID enumeration

Supports RBAC

Allows central access revocation

🗄️ 4. Migration Creation

Instead of creating a new table, the default users migration was modified.

Key points

Primary key: user_id

Unique guid

Unique email and phone

Logical delete flag

This ensured compatibility with Laravel while meeting business needs.

🌱 5. Database Seeder Creation

Seeders were created to:

Speed up development

Test authentication flows

Avoid manual DB work

Important lesson learned

Even though the model uses:

'password' => 'hashed'


Seeders must hash manually, because insert() bypasses model mutators.

Fix used
Hash::make('password123')


This is a real Laravel pitfall often asked in interviews.

🧪 6. Validation Layer (Form Requests)

Instead of validating inside controllers, Form Request classes were created.

Commands
php artisan make:request StoreUserRequest
php artisan make:request UpdateUserRequest
php artisan make:request ChangePasswordRequest

Why this approach

Keeps controllers thin

Centralizes validation

Easy to maintain and test

Industry best practice

🧾 7. Validation Responsibilities
StoreUserRequest

Required fields

Unique email and phone

Password strength

Role validation

UpdateUserRequest

Optional fields

Unique checks excluding current user

Prevents accidental overwrites

ChangePasswordRequest

Old password check

New password rules

Confirmation validation

🧠 8. User Controller Creation
Command
php artisan make:controller Api/UserController

Responsibilities

Handle HTTP requests

Delegate validation to Form Requests

Interact with User model

Return JSON responses

Controllers were intentionally kept thin.

🔐 9. User-Service APIs

User-service APIs include:

Create user

Update user

Change password

Block / activate user

Soft delete user

Fetch only non-deleted users

Important rule

All delete operations are logical deletes, never physical deletes.

🔑 10. JWT Decision (Before Auth Coding)

Before writing auth APIs, a key decision was made:

Do NOT use

Laravel sessions

Laravel Sanctum

Use

Stateless JWT authentication

Reason

Microservice-friendly

Mobile-friendly

Horizontally scalable

No server-side session storage

🔐 11. Auth-Service Creation
Command
composer create-project laravel/laravel auth-service

Run service
php artisan serve --port=8001

🔑 12. JWT Installation in Auth-Service
Commands
composer require tymon/jwt-auth
php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"
php artisan jwt:secret


This generated:

config/jwt.php

JWT_SECRET in .env

🔄 13. Major Architecture Decision
Question

Where should login APIs live?

Decision

All authentication APIs belong in auth-service.

Responsibilities split

User-service → user data, password validation, status checks

Auth-service → token creation, refresh, logout

This keeps services loosely coupled.

🔗 14. Internal Auth APIs in User-Service

Because auth-service must not access DB directly, internal APIs were created in user-service:

Validate login credentials (email or phone)

Validate user by GUID (for splash login)

These APIs:

Are not exposed publicly

Are used only by auth-service

🚫 15. No User Model in Auth-Service (Intentional)

A senior-level decision:

Auth-service should NOT have:

User model

Database

Migrations

Auth-service must remain stateless.

⚠️ 16. JWT Library Limitation Discovered

Error encountered:

JWT::fromSubject(): Argument must be JWTSubject

Cause

tymon/jwt-auth requires a class implementing JWTSubject.

🧠 17. Senior Fix – Lightweight JWTSubject

Instead of breaking architecture, a dummy AuthUser class was created:

Implements JWTSubject

Contains only guid and role

No database

No Eloquent

No migrations

Purpose:

Satisfy JWT library

Preserve stateless design

🧪 18. Auth APIs Implemented

Auth-service APIs:

Login – email or phone + password

Splash login – mobile app reopen using token

Logout – token invalidation

Each API:

Calls user-service for validation

Never touches database

Only manages JWT lifecycle

💥 19. Critical JWT TTL / Carbon Error

Error encountered:

Carbon::rawAddUnit(): Argument must be int, string given

Root cause

.env values are always strings

JWT expiry uses Carbon

Carbon requires integers

🛠️ 20. Production-Grade Fix (Config Casting)

JWT config values were explicitly cast to integers in config/jwt.php:

ttl

refresh_ttl

blacklist_grace_period

leeway

This permanently fixed the issue across environments.

🔍 21. Runtime Verification

A temporary debug endpoint was created to:

Inspect JWT config values

Verify data types at runtime

Confirmed all values were integers.

Debug route was removed after verification.

✅ 22. Final Working State

At the end:

User-service running on 8002

Auth-service running on 8001

JWT login working

Splash login working

Blocked users logged out automatically

Deleted users lose access

No shared database

Clean microservice boundaries

🎯 23. Interview-Ready Summary

I designed authentication as a dedicated auth-service using stateless JWTs.
User-service owns user data and validation, while auth-service only manages token lifecycle.
I handled JWT library constraints using a lightweight JWTSubject and fixed a real production-grade Carbon expiry issue by hardening JWT configuration.
This design supports web, mobile splash login, and future API gateway integration.

🚀 24. Future Enhancements

API Gateway routing

JWT middleware for protected APIs

React login integration

Ionic splash screen integration

Refresh token rotation

📄 License

This project is for learning and architectural reference purposes.