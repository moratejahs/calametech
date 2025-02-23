import 'package:calametech/constants/route_constants.dart';
import 'package:calametech/core/auth/login/screens/login_screen.dart';
import 'package:calametech/core/auth/signup/screens/signup_screen.dart';
import 'package:calametech/core/splash/splash_screen.dart';
import 'package:calametech/features/home/screens/home_screen.dart';
import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';

class AppRouter {
  GoRouter router = GoRouter(
    initialLocation: RouteConstants.splash,
    routes: [
      GoRoute(
          path: RouteConstants.splash,
          pageBuilder: (context, state) {
            return const MaterialPage(child: SplashScreen());
          }),
      GoRoute(
          path: RouteConstants.login,
          pageBuilder: (context, state) {
            return const MaterialPage(child: LoginScreen());
          }),
      GoRoute(
          path: RouteConstants.signup,
          pageBuilder: (context, state) {
            return const MaterialPage(child: SignupScreen());
          }),
      GoRoute(
          path: RouteConstants.home,
          pageBuilder: (context, state) {
            return const MaterialPage(child: HomeScreen());
          }),
    ],
  );
}
