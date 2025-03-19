import 'package:calamitech/config/router/app_router.dart';
import 'package:calamitech/config/theme/app_theme.dart';
import 'package:calamitech/constants/api_paths.dart';
import 'package:calamitech/core/app/cubit/navigation_cubit.dart';
import 'package:calamitech/core/auth/login/bloc/login_bloc.dart';
import 'package:calamitech/core/auth/login/repositories/login_repository.dart';
import 'package:calamitech/core/auth/signup/bloc/signup_bloc.dart';
import 'package:calamitech/core/auth/signup/repositories/signup_repository.dart';
import 'package:calamitech/core/connectivity/bloc/connectivity_bloc.dart';
import 'package:calamitech/features/ai_tips/ai_tips.dart';
import 'package:calamitech/features/ai_tips/bloc/tips_bloc.dart';
import 'package:calamitech/features/home/home.dart';
import 'package:calamitech/features/sos_reports/sos_reports.dart';
import 'package:calamitech/features/sos/bloc/sos_bloc.dart';
import 'package:calamitech/features/sos/repositories/sos_repository.dart';
import 'package:calamitech/utils/services/rest_api_service.dart';
import 'package:calamitech/utils/services/secure_storage_service.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_dotenv/flutter_dotenv.dart';
import 'package:http/http.dart' as http;

import 'core/location/cubit/location_cubit.dart';
import 'features/report/report.dart';

void main() async {
  await dotenv.load(fileName: ".env");
  runApp(const MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    final RestApiService restApiService = RestApiService(baseUrl: ApiPaths.wifiApiUrl);
    final SecureStorageService storage = SecureStorageService();
    final httpClient = http.Client();

    return MultiRepositoryProvider(
      providers: [
        RepositoryProvider<LoginRepository>(
          create: (_) => LoginRepository(restApiService: restApiService, storage: storage),
        ),
        RepositoryProvider<SignupRepository>(
          create: (_) => SignupRepository(restApiService: restApiService, storage: storage),
        ),
        RepositoryProvider<SosReportsRepository>(
          create: (_) => SosReportsRepository(httpClient: httpClient),
        ),
        RepositoryProvider<SOSRepository>(
          create: (_) => SOSRepository(restApiService: restApiService),
        ),
        RepositoryProvider<ReportRepository>(
          create: (_) => ReportRepository(httpClient: httpClient),
        ),
        RepositoryProvider<TipsRepository>(
          create: (_) => TipsRepository(httpClient),
        ),
      ],
      child: MultiBlocProvider(
        providers: [
          BlocProvider<ConnectivityBloc>(
            create: (_) => ConnectivityBloc(),
          ),
          BlocProvider<LocationCubit>(
            create: (_) => LocationCubit(),
          ),
          BlocProvider<NavigationCubit>(
            create: (_) => NavigationCubit(),
          ),
          BlocProvider<LoginBloc>(
            create: (context) => LoginBloc(
              loginRepository: context.read<LoginRepository>(),
              storage: storage,
            ),
          ),
          BlocProvider<SignupBloc>(
              create: (context) => SignupBloc(
                    signupRepository: context.read<SignupRepository>(),
                    storage: storage,
                  )),
          BlocProvider<SosReportsBloc>(
              create: (context) => SosReportsBloc(
                    sosReportsRepository: context.read<SosReportsRepository>(),
                    storage: storage,
                  )),
          BlocProvider<SosFeaturedReportsBloc>(
              create: (context) => SosFeaturedReportsBloc(
                    sosReportsRepository: context.read<SosReportsRepository>(),
                    storage: storage,
                  )),
          BlocProvider<SosRecoReportsBloc>(
              create: (context) => SosRecoReportsBloc(
                    sosReportsRepository: context.read<SosReportsRepository>(),
                    storage: storage,
                  )),
          BlocProvider<SosBloc>(
              create: (context) => SosBloc(
                    sosRepository: context.read<SOSRepository>(),
                    storage: storage,
                  )),
          BlocProvider<ReportBloc>(
              create: (context) => ReportBloc(
                    reportRepository: context.read<ReportRepository>(),
                    storage: storage,
                  )),
          BlocProvider<TipsBloc>(
              create: (context) => TipsBloc(
                    tipsRepository: context.read<TipsRepository>(),
                    storage: storage,
                  )),
        ],
        child: MaterialApp.router(
          theme: AppTheme.lightTheme,
          routerConfig: AppRouter().router,
        ),
      ),
    );
  }
}
