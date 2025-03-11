import 'package:calamitech/config/theme/app_theme.dart';
import 'package:calamitech/constants/asset_paths.dart';
import 'package:calamitech/constants/route_constants.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:go_router/go_router.dart';

import '../home.dart';

class HomeScreen extends StatefulWidget {
  const HomeScreen({super.key});

  @override
  State<HomeScreen> createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  @override
  void initState() {
    context.read<SosRecoReportsBloc>().add(SosRecoReportsFetched());
    context.read<SosFeaturedReportsBloc>().add(SosFeaturedReportsFetched());
    super.initState();
  }

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.all(16.0),
      child: Column(
        mainAxisAlignment: MainAxisAlignment.start,
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Flexible(
            child: BlocBuilder<SosFeaturedReportsBloc, SosFeaturedReportsState>(
              builder: (context, state) {
                if (state is SosFeaturedReportsLoading) {
                  return const Center(
                    child: CircularProgressIndicator(),
                  );
                }

                if (state is SosFeaturedReportsLoaded) {
                  return SizedBox(
                    height: 300,
                    child: ListView.builder(
                      scrollDirection: Axis.horizontal,
                      itemCount: state.sosFeaturedReports.length,
                      itemBuilder: (context, index) {
                        return SosCard(
                          sosReport: state.sosFeaturedReports[index],
                          fullWidth: true,
                        );
                      },
                    ),
                  );
                }

                if (state is SosFeaturedReportsError) {
                  return Center(
                    child: Text(state.message),
                  );
                }

                return const SizedBox();
              },
            ),
          ),
          GridView.count(
            crossAxisCount: 2,
            shrinkWrap: true,
            childAspectRatio: 2.0,
            children: [
              Tip(color: AppTheme.primaryColor, image: AssetPaths.fire, title: 'Fire Tips'),
              Tip(color: Colors.green[400], image: AssetPaths.home, title: 'Flood Tips'),
              Tip(color: AppTheme.primaryColor, image: AssetPaths.safety, title: 'Safety Tips'),
              const Tip(color: Colors.blueGrey, image: AssetPaths.more, title: 'More Tips'),
            ],
          ),
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              const Text(
                'Recommendations for you',
                style: TextStyle(
                  fontSize: 20.0,
                ),
              ),
              TextButton(
                onPressed: () {
                  GoRouter.of(context).go(RouteConstants.sosReports);
                },
                child: const Text('see more'),
              ),
            ],
          ),
          Flexible(
            child: BlocBuilder<SosRecoReportsBloc, SosRecoReportsState>(
              builder: (context, state) {
                if (state is SosRecoReportsLoading) {
                  return const Center(
                    child: CircularProgressIndicator(),
                  );
                }

                if (state is SosRecoReportsLoaded) {
                  return SizedBox(
                    height: 250,
                    child: ListView.builder(
                      scrollDirection: Axis.horizontal,
                      itemCount: state.sosRecoReports.length,
                      itemBuilder: (context, index) {
                        return SosCard(
                          sosReport: state.sosRecoReports[index],
                        );
                      },
                    ),
                  );
                }

                if (state is SosRecoReportsError) {
                  return Center(
                    child: Text(state.message),
                  );
                }

                return const SizedBox();
              },
            ),
          ),
        ],
      ),
    );
  }
}
