import 'package:calamitech/constants/route_constants.dart';
import 'package:calamitech/core/auth/login/screens/login_screen.dart';
import 'package:calamitech/core/auth/signup/screens/signup_screen.dart';
import 'package:calamitech/core/splash/splash_screen.dart';
import 'package:calamitech/features/home/screens/home_screen.dart';
import 'package:calamitech/features/profile/screens/profile_screen.dart';
import 'package:calamitech/features/report/screens/report_screen.dart';
import 'package:calamitech/features/sos/screens/sos_screen.dart';
import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import '../../core/app/app_scaffold.dart';

class AppRouter {
  GoRouter router = GoRouter(
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
      ShellRoute(
        builder: (context, state, child) => AppScaffold(child: child),
        routes: [
          GoRoute(
              path: RouteConstants.home,
              pageBuilder: (context, state) {
                return const MaterialPage(child: HomeScreen());
              }),
          GoRoute(
              path: RouteConstants.profile,
              pageBuilder: (context, state) {
                return const MaterialPage(child: ProfileScreen());
              }),
          GoRoute(
              path: RouteConstants.report,
              pageBuilder: (context, state) {
                return const MaterialPage(child: ReportScreen());
              }),
          GoRoute(
              path: RouteConstants.sos,
              pageBuilder: (context, state) {
                return const MaterialPage(child: SOSScreen());
              }),
        ],
      ),
    ],
  );
}


