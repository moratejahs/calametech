import 'package:calamitech/constants/route_constants.dart';
import 'package:calamitech/core/auth/login/screens/login_screen.dart';
import 'package:calamitech/core/auth/signup/screens/signup_screen.dart';
import 'package:calamitech/features/splash/screens/splash_screen.dart';
import 'package:calamitech/features/home/home.dart';
import 'package:calamitech/features/profile/screens/profile_screen.dart';
import 'package:calamitech/features/report/screens/report_screen.dart';
import 'package:calamitech/features/sos/screens/sos_screen.dart';
import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import '../../core/app/app_scaffold.dart';
import '../../utils/services/secure_storage_service.dart';

class AppRouter {
  final SecureStorageService storage = SecureStorageService();

  late final GoRouter router = GoRouter(
    initialLocation: RouteConstants.splash,
    redirect: (context, state) async {
      final currentRoute = state.uri.toString();
      debugPrint('AppRouter: current route: $currentRoute');

      final userJson = await storage.readValue('user');

      return Future.sync(() {
        if (userJson == null) {
          // Redirect unauthenticated users to login
          if (currentRoute != RouteConstants.login && currentRoute != RouteConstants.signup && currentRoute != RouteConstants.splash) {
            return RouteConstants.login;
          }
        } else {
          debugPrint('AppRouter: current user: $userJson');

          // Redirect authenticated users away from login/signup
          if (currentRoute == RouteConstants.login || currentRoute == RouteConstants.signup || currentRoute == RouteConstants.splash) {
            return RouteConstants.home;
          }
        }
        return null;
      });
    },
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
