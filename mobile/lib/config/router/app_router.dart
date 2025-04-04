import 'package:calamitech/constants/route_constants.dart';
import 'package:calamitech/core/auth/login/screens/login_screen.dart';
import 'package:calamitech/core/auth/signup/screens/signup_screen.dart';
import 'package:calamitech/features/ai_tips/view/tips_screen.dart';
import 'package:calamitech/features/report/view/view.dart';
import 'package:calamitech/features/splash/screens/splash_screen.dart';
import 'package:calamitech/features/home/home.dart';
import 'package:calamitech/features/profile/screens/profile_screen.dart';
import 'package:calamitech/features/sos_reports/sos_reports.dart';
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
        builder: (context, state, child) {
          final noScaffoldRoutes = {
            RouteConstants.report,
            RouteConstants.sosReports,
            RouteConstants.tips,
            RouteConstants.fireTips,
            RouteConstants.floodTips,
            RouteConstants.safetyTips,
          };

          if (noScaffoldRoutes.contains(state.uri.path)) {
            return child;
          }

          return AppScaffold(child: child);
        },
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
              path: RouteConstants.sosReports,
              pageBuilder: (context, state) {
                return const MaterialPage(child: SosReportsScreen());
              }),
          GoRoute(
              path: RouteConstants.sos,
              pageBuilder: (context, state) {
                return const MaterialPage(child: SOSScreen());
              }),
          GoRoute(
              path: RouteConstants.report,
              pageBuilder: (context, state) {
                return const MaterialPage(child: ReportScreen());
              }),
          GoRoute(
              path: RouteConstants.tips,
              pageBuilder: (context, state) {
                return const MaterialPage(child: TipsScreen());
              }),
          GoRoute(
              path: RouteConstants.fireTips,
              pageBuilder: (context, state) {
                return const MaterialPage(
                    child: TipsScreen(
                  tipType: 'fire_tips',
                ));
              }),
          GoRoute(
              path: RouteConstants.floodTips,
              pageBuilder: (context, state) {
                return const MaterialPage(child: TipsScreen(tipType: 'flood_tips'));
              }),
          GoRoute(
              path: RouteConstants.safetyTips,
              pageBuilder: (context, state) {
                return const MaterialPage(child: TipsScreen(tipType: 'safety_tips'));
              }),
        ],
      ),
    ],
  );
}
