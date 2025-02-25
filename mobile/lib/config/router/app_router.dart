import 'package:calamitech/constants/route_constants.dart';
import 'package:calamitech/core/auth/login/screens/login_screen.dart';
import 'package:calamitech/core/auth/signup/screens/signup_screen.dart';
import 'package:calamitech/features/splash/screens/splash_screen.dart';
import 'package:calamitech/features/home/screens/home_screen.dart';
import 'package:calamitech/features/profile/screens/profile_screen.dart';
import 'package:calamitech/features/report/screens/report_screen.dart';
import 'package:calamitech/features/sos/screens/sos_screen.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:go_router/go_router.dart';
import '../../core/app/app_scaffold.dart';
import '../../core/auth/login/bloc/login_bloc.dart';
import '../../utils/services/secure_storage_service.dart';

class AppRouter {
  GoRouter router = GoRouter(
    redirect: (context, state) async {
      final currentRoute = state.uri.toString();
      debugPrint('Current route: $currentRoute');

      final storage = SecureStorageService();
      final token = await storage.readValue('token');

      // TODO: Implement persistent auth state
      if(token == null) {
        return RouteConstants.login;
      }

      if (currentRoute == RouteConstants.splash || currentRoute == RouteConstants.login || currentRoute == RouteConstants.signup) {
        // if (token != null) {
        //   return RouteConstants.home;
        // }

        return null;
      }


      final loginState = context.read<LoginBloc>().state;

      if (loginState is LoginInitial || loginState is LoginLoading || loginState is LoginFailure) {
        return RouteConstants.login;
      }

      return null;
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
