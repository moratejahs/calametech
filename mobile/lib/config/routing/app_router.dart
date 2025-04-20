import 'package:calamitech/config/routing/app_routes.dart';
import 'package:calamitech/config/routing/auth_guard.dart';
import 'package:calamitech/features/auth/presentation/login_screen.dart';
import 'package:calamitech/features/auth/presentation/register_screen.dart';
import 'package:calamitech/features/home/presentation/home_screen.dart';
import 'package:calamitech/features/profile/presentation/profile_screen.dart';
import 'package:calamitech/features/splash/screens/splash_screen.dart';
import 'package:flutter/material.dart';

class AppRouter {
  static Route<dynamic> generateRoute(RouteSettings settings) {
    switch (settings.name) {
      case AppRoutes.splash:
        return MaterialPageRoute(builder: (_) => const SplashScreen());
      case AppRoutes.login:
        return MaterialPageRoute(builder: (_) => const LoginScreen());
      case AppRoutes.register:
        return MaterialPageRoute(builder: (_) => const RegisterScreen());
      case AppRoutes.home:
        return MaterialPageRoute(
          builder: (_) => const AuthGuard(child: HomeScreen()),
        );
      case AppRoutes.profile:
        return MaterialPageRoute(
          builder: (_) => const AuthGuard(child: ProfileScreen()),
        );
      default:
        return MaterialPageRoute(
          builder:
              (_) => Scaffold(
            body: Center(
              child: Text('No route defined for ${settings.name}'),
            ),
          ),
        );
    }
  }
}
