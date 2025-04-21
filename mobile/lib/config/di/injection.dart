import 'package:calamitech/core/utils/services/sos_service.dart';
import 'package:calamitech/core/utils/services/tips_service.dart';
import 'package:calamitech/features/report/blocs/report_bloc.dart';
import 'package:calamitech/features/report/repositories/sos_repository.dart';
import 'package:calamitech/features/tips/blocs/tips_bloc.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_dotenv/flutter_dotenv.dart';
import 'package:http/http.dart' as http;
import 'package:calamitech/core/connectivity/bloc/connectivity_bloc.dart';
import 'package:calamitech/core/location/cubit/location_cubit.dart';
import 'package:calamitech/core/utils/services/secure_storage_service.dart';
import 'package:calamitech/core/utils/services/auth_user_service.dart';
import 'package:calamitech/features/auth/blocs/auth_bloc.dart';
import 'package:calamitech/features/auth/repositories/auth_repository.dart';
import 'package:calamitech/features/news/blocs/news_bloc.dart';
import 'package:calamitech/features/news/repositories/news_repository.dart';
import 'package:calamitech/features/tips/repositories/tips_repository.dart';

List<RepositoryProvider> repositoryProviders() {
  final httpClient = http.Client();
  final storage = SecureStorageService();

  return [
    RepositoryProvider<AuthUserService>(
      create: (_) => AuthUserService(storage: storage),
    ),
    RepositoryProvider<TipsService>(
      create: (_) => TipsService(storage: storage),
    ),
    RepositoryProvider<SosService>(
      create: (_) => SosService(storage: storage),
    ),
    RepositoryProvider<AuthRepository>(
      create: (context) => AuthRepository(
        httpClient: httpClient,
        authUserService: context.read<AuthUserService>(),
      ),
    ),
    RepositoryProvider<NewsRepository>(
      create: (context) => NewsRepository(
        httpClient: httpClient,
        authUserService: context.read<AuthUserService>(),
      ),
    ),
    RepositoryProvider<TipsRepository>(
      create: (context) => TipsRepository(
        httpClient: httpClient,
        token: dotenv.env['AI_API_KEY'] ?? '',
        tipsService: context.read<TipsService>(),
      ),
    ),
    RepositoryProvider<SosRepository>(
      create: (context) => SosRepository(
        httpClient: httpClient,
        authUserService: context.read<AuthUserService>(),
        sosService: context.read<SosService>(),
      ),
    ),
  ];
}

List<BlocProvider> blocProviders() {
  return [
    BlocProvider<AuthBloc>(
      create: (context) => AuthBloc(
        authRepository: context.read<AuthRepository>(),
        authUserService: context.read<AuthUserService>(),
      ),
    ),
    BlocProvider<ConnectivityBloc>(
      create: (_) => ConnectivityBloc(),
    ),
    BlocProvider<LocationCubit>(
      create: (_) => LocationCubit(),
    ),
    BlocProvider<NewsBloc>(
      create: (context) =>
          NewsBloc(newsRepository: context.read<NewsRepository>()),
    ),
    BlocProvider<TipsBloc>(
      create: (context) => TipsBloc(
        tipsRepository: context.read<TipsRepository>(),
      ),
    ),
    BlocProvider<ReportBloc>(
      create: (context) => ReportBloc(
        sosRepository: context.read<SosRepository>(),
      ),
    ),
  ];
}
