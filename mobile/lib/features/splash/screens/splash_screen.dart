import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:go_router/go_router.dart';

import '../../../config/theme/app_theme.dart';
import '../../../constants/asset_paths.dart';
import '../../../constants/route_constants.dart';
import '../../../core/auth/login/bloc/login_bloc.dart';
import '../../../core/auth/login/models/user.dart';
import '../../../utils/services/secure_storage_service.dart';

class SplashScreen extends StatefulWidget {
  const SplashScreen({super.key});

  @override
  State<SplashScreen> createState() => _SplashScreenState();
}

class _SplashScreenState extends State<SplashScreen> {
  final SecureStorageService storage = SecureStorageService();

  @override
  void initState() {
    super.initState();
    _initializeAuth();
  }

  Future<void> _initializeAuth() async {
    final authUser = await storage.readValue('user');

    if (authUser != null) {
      context
          .read<LoginBloc>()
          .add(UserAlreadyLoggedIn(User.fromJson(authUser)));

      // Delay for a smooth transition
      await Future.delayed(const Duration(seconds: 2));
      if (mounted) context.go(RouteConstants.home);
    }

    await Future.delayed(const Duration(seconds: 2));
    if (mounted) context.go(RouteConstants.login);
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Container(
        decoration: BoxDecoration(
          gradient: LinearGradient(
            begin: Alignment.topCenter,
            end: Alignment.bottomCenter,
            colors: [AppTheme.primaryColor, Colors.blue[50]!],
          ),
        ),
        child: Center(
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            crossAxisAlignment: CrossAxisAlignment.center,
            spacing: 10,
            children: [
              Image.asset(
                AssetPaths.appLogo,
                width: 150,
                height: 150,
                fit: BoxFit.contain,
              ),
              const CircularProgressIndicator(
                valueColor: AlwaysStoppedAnimation<Color>(Colors.white),
              )
            ],
          ),
        ),
      ),
    );
  }
}
