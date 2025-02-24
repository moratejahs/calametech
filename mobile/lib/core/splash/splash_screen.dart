import 'package:calametech/constants/route_constants.dart';
import 'package:calametech/utils/services/secure_storage_service.dart';
import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';

class SplashScreen extends StatefulWidget {
  const SplashScreen({super.key});

  @override
  State<SplashScreen> createState() => _SplashScreenState();
}

class _SplashScreenState extends State<SplashScreen> {
  void initSplash() {
    Future.delayed(const Duration(seconds: 10), () async {
      final storage = SecureStorageService();
      final token = await storage.readValue('token') as String?;

      if (!mounted) return;

      if (token != null) {
        debugPrint('Token exists, redirecting to home screen');
        GoRouter.of(context).go(RouteConstants.home);
        return;
      } else {
        debugPrint('Token is null, redirecting to login screen');
        GoRouter.of(context).go(RouteConstants.login);
        return;
      }
    });
  }

  @override
  void initState() {
    initSplash();
    super.initState();
  }

  @override
  Widget build(BuildContext context) {
    return const Scaffold(
      body: Center(
        child: Text('Splash Screen'),
      ),
    );
  }
}
