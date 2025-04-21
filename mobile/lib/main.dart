import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_dotenv/flutter_dotenv.dart';
import 'package:calamitech/config/di/injection.dart';
import 'package:calamitech/config/theme/app_theme.dart';
import 'package:calamitech/config/routing/app_routes.dart';
import 'package:calamitech/config/routing/app_router.dart';

Future<void> main() async {
  WidgetsFlutterBinding.ensureInitialized();
  await dotenv.load(fileName: ".env");
  runApp(const MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MultiRepositoryProvider(
      providers: repositoryProviders(),
      child: MultiBlocProvider(
        providers: blocProviders(),
        child: MaterialApp(
          debugShowCheckedModeBanner: false,
          theme: AppTheme.lightTheme,
          initialRoute: AppRoutes.splash,
          onGenerateRoute: AppRouter.generateRoute,
        ),
      ),
    );
  }
}
