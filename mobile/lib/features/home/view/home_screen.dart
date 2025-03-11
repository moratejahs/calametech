import 'package:calamitech/config/theme/app_theme.dart';
import 'package:calamitech/constants/asset_paths.dart';
import 'package:calamitech/features/home/widgets/tips.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';

import '../home.dart';

class HomeScreen extends StatefulWidget {
  const HomeScreen({super.key});

  @override
  State<HomeScreen> createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  @override
  void initState() {
    context.read<SosReportsBloc>().add(SosReportsFetched());
    super.initState();
  }

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.all(16.0),
      child: Column(
        mainAxisAlignment: MainAxisAlignment.start,
        crossAxisAlignment: CrossAxisAlignment.start,
        spacing: 8.0,
        children: [
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
          const Text(
            'Recommendations for you',
            style: TextStyle(
              fontSize: 20.0,
            ),
          ),
          Flexible(
            child: BlocBuilder<SosReportsBloc, SosReportsState>(
              builder: (context, state) {
                if (state is SosReportsLoading) {
                  return const Center(
                    child: CircularProgressIndicator(),
                  );
                }

                if (state is SosReportsLoaded) {
                  return ListView.builder(
                    itemCount: state.sosReports.length,
                    itemBuilder: (context, index) {
                      final sosReport = state.sosReports[index];
                      return ListTile(
                        title: Text(sosReport.status),
                        subtitle: Text(sosReport.date),
                      );
                    },
                  );
                }

                if (state is SosReportsError) {
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
