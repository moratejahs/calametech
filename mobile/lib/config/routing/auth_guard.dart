import 'package:calamitech/features/auth/presentation/login_screen.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:calamitech/features/auth/blocs/auth_bloc.dart';
import 'package:calamitech/config/routing/app_routes.dart';

class AuthGuard extends StatelessWidget {
  final Widget child;

  const AuthGuard({super.key, required this.child});

  @override
  Widget build(BuildContext context) {
    return BlocConsumer<AuthBloc, AuthState>(
      listenWhen: (context, state) =>
          state is AuthUnAuthenticated || state is AuthFailure,
      listener: (context, state) {
        if (state is AuthUnAuthenticated || state is AuthFailure) {
          Navigator.pushNamedAndRemoveUntil(
            context,
            AppRoutes.login,
            (route) => false,
          );
        }
      },
      buildWhen: (context, state) =>
          state is AuthAuthenticated ||
          state is AuthUnAuthenticated ||
          state is AuthFailure,
      builder: (context, state) {
        if (state is AuthAuthenticated) {
          return child;
        }

        return const LoginScreen();
      },
    );
  }
}
