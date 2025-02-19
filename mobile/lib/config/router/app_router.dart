import 'package:calametech/constants/route_constants.dart';
import 'package:calametech/core/auth/login/bloc/login_bloc.dart';
import 'package:calametech/core/auth/login/screens/login_screen.dart';
import 'package:calametech/core/splash/splash_screen.dart';
import 'package:calametech/features/home/screens/home_screen.dart';
import 'package:calametech/utils/services/secure_storage_service.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:go_router/go_router.dart';

class AppRouter {
  GoRouter router = GoRouter(
    initialLocation: RouteConstants.home,
    routes: [
      GoRoute(
          path: RouteConstants.splash,
          pageBuilder: (context, state) {
            return const MaterialPage(child: SplashScreen());
          }),
      GoRoute(
          path: RouteConstants.home,
          pageBuilder: (context, state) {
            return const MaterialPage(child: HomeScreen());
          }),
      GoRoute(
          path: RouteConstants.login,
          pageBuilder: (context, state) {
            return const MaterialPage(child: LoginScreen());
          })
    ],
    redirect: (context, state) async {
      final loginBlocState = context.read<LoginBloc>().state;
      debugPrint('loginBlocState: $loginBlocState');

      final storage = SecureStorageService();
      final token = await storage.readValue('token');
      debugPrint('token: $token');
      final tokenExists = token != null && token.isNotEmpty;

      if (loginBlocState is LoginSuccess && tokenExists) {
        return RouteConstants.home;
      }

      if (loginBlocState is LoginInitial || (loginBlocState is LoginFailure && !tokenExists)) {
        return RouteConstants.login;
      }

      if (loginBlocState is LoginFailure && tokenExists) {
        return RouteConstants.home;
      }

      return null;
    },
  );
}
